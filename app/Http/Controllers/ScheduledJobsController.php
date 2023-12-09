<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Trade;
use App\Helper\Helper;
use App\Jobs\EmailJob;
use App\Jobs\TradeSync;
use App\Models\Account;
use App\Models\MtServer;
use App\Jobs\SendEmailJob;
use App\Models\GrowthFund;
use App\Jobs\TradeCloseJob;
use App\Models\CustomerKycs;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\AccountMetric;
use App\Services\NewsService;
use App\Jobs\ProfitCheckerJob;
use App\Constants\AppConstants;
use App\Models\ApprovalCategory;
use App\Services\AccountService;
use App\Constants\EmailConstants;
use Illuminate\Support\Facades\Log;
use App\Models\TargetReachedAccount;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class ScheduledJobsController extends Controller
{
    //

    public $restrictedNews;
    public $accountService;
    public function __construct(AccountService $accountService)
    {

        $this->accountService = $accountService;
    }


    public function mtinit()
    {
        $sessionToken = [];

        $servers = MtServer::all();
        foreach ($servers as $server) {
            try {
                $response = Helper::mt4init($server);
                if ($response['code'] == 200) {
                    $sessionToken[$server->id] = $response['data'];
                } else {
                    $sessionToken[$server->id] = $response['message'];
                }
            } catch (Exception $exception) {
                Log::error("MT4 init loop", [$exception]);
                $sessionToken[$server->id] = "Internal server error";
            }
        }

        return $sessionToken;
    }

    public function ping()
    {
        $servers = MtServer::all();
        $sessionToken = [];
        foreach ($servers as $server) {
            $sessionToken[] = Helper::ping($server);
        }
    }


    public function weeklyTradeCloseV3()
    {
        $servers = MtServer::all();

        foreach ($servers as $server) {

            $accounts = $server->accounts;
            $url = $server->url;
            $sessionToken = $server->login;
            $activeTrades = [];
            foreach ($accounts as $account) {

                $planRules = $account->planRules();


                $endingDate = Carbon::parse($account->currentSubscription->ending_at);
                $now = Carbon::now();
                $endOfDay = $now->copy()->endOfDay();




                // ! Subscription ends today

                if (((isset($planRules['WH']) && !($planRules['WH']['value'])) || (!isset($planRules['WH']))) || ($endOfDay->gte($endingDate))) {  //!Weekend holding nai or weekend holding false
                    //!get all active trades for that account
                    $loginTrades = Redis::smembers('orders:' . $account->login . ':working');

                    if ($loginTrades != null) {
                        array_push($activeTrades,  ...$loginTrades);
                    } //!Make a long list of all active trades
                }
            }

            $activeTrades = array_map('intval', $activeTrades);


            //Helper::discordAlert("**Trade Close**:\nTotal Running Trades : " . count($activeTrades));

            //!get all trade reports for lots and price
            $tradesReport = Http::acceptJson()->post($url . "/trades/report?token=" . $sessionToken, [

                'orders' => $activeTrades
            ]);
            $tradesReport = $tradesReport['data'];

            $tradesReport = collect($tradesReport);
            $i = 0;
            foreach ($tradesReport->chunk(50) as $tradeCloseDetails) {
                $t =  $tradeCloseDetails->values();
                $trades = $t->all();
                $tradeCloseInfo = [
                    'server_url' => $accounts[0]->plan->server->url,
                    'sessionToken' => $sessionToken,
                    'trades' =>  $trades,
                ];
                TradeCloseJob::dispatch($tradeCloseInfo)->onQueue(AppConstants::QUEUE_TRADE_CLOSE_JOB)->delay(now()->addSeconds($i * 10));
                $i++;
            }
        }
    }
    public function breachAccountTradeSync()
    {

        $servers = MtServer::all();
        foreach ($servers as $server) {



            $accounts = $server->accounts;


            $accounts = $accounts->where('breachedby', '!=',  null);
            $accounts = collect($accounts->all());


            $url = $server->url;
            $sessionToken = $server->login;

            $logins = $accounts->pluck('login');

            $reportTrades = Http::acceptJson()->post($url . "/users/report?token=" . $sessionToken, [

                'logins' => $logins,
                "from" => Carbon::now()->subHours(400)->format('Y.m.d H:i'),
                "to" => Carbon::now()->addHours(24)->format('Y.m.d H:i'),
                "types" => "0,1"

            ]);
            $reportTrades = json_decode($reportTrades, 1);

            // $telescope[] = "All closed Trades:  $reportTrades;

            $reportTrades = collect($reportTrades);


            $reportTrades = $reportTrades->groupBy('login');


            foreach ($accounts as $account) {

                $telescope[] = "Account ID: " . $account->id;

                $login = (string)$account->login;
                // $login = '1820724649';
                $accountTrades = $reportTrades->has($login) ? $reportTrades[$login] : collect([]);

                $workingTrades = Redis::smembers('orders:' . $login . ':working');

                if ($workingTrades != null) {
                    $telescope[] = "Working Trade Count: " . count($workingTrades) . "";

                    $workingTrades = array_map('intval',  $workingTrades);

                    $workingtradeReports = Http::acceptJson()->post($url . "/trades/report?token=" . $sessionToken,  ['orders' => $workingTrades]);
                    $accountTrades = $accountTrades->toArray();
                    array_push($accountTrades,  ...$workingtradeReports['data']);

                    // $telescope[] = $accountTrades;
                }
                // return $accountTrades;

                foreach ($accountTrades as $report) {

                    $telescope[] = "Trade Ticket- $report[ticket]";



                    $report['account_id'] = $account->id;
                    if ($report['swap'] < 0) {
                        $report['swap'] = 0;
                    } else if ($report['swap'] > 2047483647) {
                        $report['swap'] = 0;
                    }
                    $report['swap'] = round($report['swap'], 4);

                    $update = Trade::updateOrCreate([
                        'ticket' => $report['ticket']

                        //! also think about if this data can be generated on the fly like lots count

                    ], $report);

                    // return $update->wasRecentlyCreated;
                    if ($update->wasRecentlyCreated) {


                        $reportDate = Carbon::createFromTimestamp($report["open_time"])->toDateString();
                        // $reportDate = date('Y-m-d', $report["open_time"]);


                        $createdMetric = AccountMetric::where('account_id', $account->id)->whereDate('metricDate', $reportDate)->first();
                        if ($createdMetric == null) {

                            return [$account->id, $reportDate];
                        }


                        // return  $test;

                        $createdMetric->increment('trades');
                        // return $createdMetric;
                        // $createdMetric->save();
                        $telescope[] = "+++++++++  $report[ticket] Trade was missing ++++++++++++";
                        $telescope[] = "Trade count increased for metric date : $reportDate";
                    }
                }
            }
        }
        return $telescope;
    }

    public function tradeSyncV2()
    {

        $servers = MtServer::all();
        Helper::discordAlert("**Trade Sync Running**");

        foreach ($servers as $server) {



            $accounts = $server->accounts;


            $accounts = $accounts->where('breachedby',  null);
            $accounts = collect($accounts->all());


            $url = $server->url;
            $sessionToken = $server->login;

            $logins = $accounts->pluck('login');

            $reportTrades = Http::acceptJson()->post($url . "/users/report?token=" . $sessionToken, [

                'logins' => $logins,
                "from" => Carbon::now()->subHours(60)->format('Y.m.d H:i'),
                "to" => Carbon::now()->addHours(24)->format('Y.m.d H:i'),
                "types" => "0,1"

            ]);
            $reportTrades = json_decode($reportTrades, 1);

            // $telescope[] = "All closed Trades:  $reportTrades;

            $reportTrades = collect($reportTrades);


            $reportTrades = $reportTrades->groupBy('login');


            foreach ($accounts as $account) {

                $telescope[] = "Account ID: " . $account->id;

                $login = (string)$account->login;
                // $login = '1820724649';
                $accountTrades = $reportTrades->has($login) ? $reportTrades[$login] : collect([]);

                $workingTrades = Redis::smembers('orders:' . $login . ':working');

                if ($workingTrades != null) {
                    $telescope[] = "Working Trade Count: " . count($workingTrades) . "";

                    $workingTrades = array_map('intval',  $workingTrades);

                    $workingtradeReports = Http::acceptJson()->post($url . "/trades/report?token=" . $sessionToken,  ['orders' => $workingTrades]);
                    $accountTrades = $accountTrades->toArray();
                    array_push($accountTrades,  ...$workingtradeReports['data']);

                    // $telescope[] = $accountTrades;
                }
                // return $accountTrades;

                foreach ($accountTrades as $report) {

                    $telescope[] = "Trade Ticket- $report[ticket]";



                    $report['account_id'] = $account->id;
                    if ($report['swap'] < 0) {
                        $report['swap'] = 0;
                    } else if ($report['swap'] > 2047483647) {
                        $report['swap'] = 0;
                    }
                    $report['swap'] = round($report['swap'], 4);

                    $update = Trade::updateOrCreate([
                        'ticket' => $report['ticket']

                        //! also think about if this data can be generated on the fly like lots count

                    ], $report);

                    // return $update->wasRecentlyCreated;
                    if ($update->wasRecentlyCreated) {


                        $reportDate = Carbon::createFromTimestamp($report["open_time"])->toDateString();
                        // $reportDate = date('Y-m-d', $report["open_time"]);


                        $createdMetric = AccountMetric::where('account_id', $account->id)->whereDate('metricDate', $reportDate)->first();
                        if ($createdMetric == null) {

                            return [$account->id, $reportDate];
                        }
                        $createdMetric->isActiveTradingDay = true;
                        $createdMetric->trades = $createdMetric->trades + 1;
                        $createdMetric->save();

                        // return $createdMetric;
                        // $createdMetric->save();
                        $telescope[] = "+++++++++  $report[ticket] Trade was missing ++++++++++++";
                        $telescope[] = "Trade count increased for metric date : $reportDate";
                    }
                }
            }
        }
        return $telescope;
    }



    public function profitChecker(NewsService $newsService)
    {

        Helper::discordAlert("**Profit Checker Start : **" . Carbon::now());
        $accounts = Account::with(['currentSubscription', 'plan', 'customer'])->where('breached', '0')->get();

        $controller = new \App\Http\Controllers\AccountController();

        $this->restrictedNews = $newsService->weeksNews(5);

        foreach ($accounts as $account) {
            $debug = [];
            $debug[] = "---------------------------------------------------------------------------------------------------";
            $debug[] = "Account Id " . $account->id . " Login: " . $account->login;
            $targetFlag = true;
            $planRules = $account->planRules();

            $accountPackage = $account->plan->type;


            //! Run profit checker for express accounts only on Friday
            if (($accountPackage == Plan::EX_DEMO) || ($accountPackage == Plan::EX_REAL)) {
                $debug[] = "Express Account";
                $today = Carbon::now();
                if (
                    ($today->dayOfWeek != (Carbon::SATURDAY || Carbon::SUNDAY))
                ) {
                    $debug[] = "Not friday/saturday. So skipped";
                    continue;
                };
            } elseif ($accountPackage == Plan::EV_P1) {
                //Duplicate Check
                ProfitCheckerJob::dispatch($account)->onQueue(AppConstants::QUEUE_PROFIT_CHECKER_JOB);
                continue;
            }

            $redisData = json_decode(Redis::get('margin:' . $account->login), 1);

            $currentBalance = $redisData['balance'];
            $currentEquity = $redisData['equity'];

            $accountCurrentBalance = $redisData['balance'];

            $acntStartingBalance = $account->starting_balance;
            $accountProfit = $currentBalance - $acntStartingBalance;
            $profitPercentage = ($accountProfit / $acntStartingBalance) * 100;




            $endingDate = Carbon::parse($account->currentSubscription->ending_at);
            $now = Carbon::now();

            $debug[] = "Now " . $now . " End Date" . $endingDate;



            // ! Subscription ends today
            if ($now->gte($endingDate)) {

                $debug[] = "Subscription End today" . $endingDate . "Current Date" . $now;

                if ($accountProfit > 0) {  //!Acocunt in profit
                    $debug[] = "In Profit";

                    if (isset($planRules['PT'])) {

                        if (isset($planRules['AGF'])) {
                            // ! If account has GrowthFund add that also to profit amount

                            $growthFunds = $account->growthFund;
                            if ($growthFunds != null) {

                                $growthFundAmount = $growthFunds->sum('amount');
                                $accountProfit = $accountProfit + $growthFundAmount;
                            }
                        }
                        $debug[] = "Profit : $accountProfit";
                        $profitPercentage = ($accountProfit / $acntStartingBalance) * 100;

                        $debug[] = "Profit Percentage: " . $profitPercentage;
                        $debug[] = "Profit Target Percentage :" . $planRules['PT']['value'];
                        if ($profitPercentage >= $planRules['PT']['value']) {
                            //!Target Reached
                            $approvalCategoryId = 1;  //! when full account target reached
                            if (isset($planRules['MTD'])) {
                                if (($account->tradingDays()) < $planRules['MTD']['value']) {
                                    $debug[] = "Minimum trading days : " . $account->tradingDays();
                                    $debug[] = "MTD not fulfilled";
                                    $targetFlag = false; // ! Minimum trading days condition not fulfilled
                                }
                            }
                            // if (isset($planRules['CR'])) { //TODO: CR to CRD when consistency is required
                            //     if (!($account->isConsistent($planRules['CRD']['value']))) {


                            //         $debug[] = "Not consistent";
                            //         $targetFlag = false; // ! is not consistent
                            //     }
                            // }
                            $runningTrades = Redis::smembers('orders:' .  $account->login . ':working');
                            if (!empty($runningTrades)) {
                                $debug[] = "Has Running Trades : " . json_encode($runningTrades, JSON_PRETTY_PRINT);
                                $targetFlag = false; //! Has running trades
                            }
                        } else {
                            //  ! Profit Target not fulfilled
                            $targetFlag = false;
                        }
                        $debug[] = $targetFlag ? "Target Flag : True" : "Target Flag : False";

                        if ($targetFlag) {

                            $this->profitTargetReached($account, $acntStartingBalance, $currentBalance, $currentEquity, $profitPercentage);
                            continue;
                        }
                    }
                    $approvalCategoryId = 2; //! Monthly partial profit
                    $debug[] = "Account in partial profit";

                    if ((isset($planRules['MTD']) && ($account->tradingDays() >= $planRules['MTD']['value'])) || !isset($planRules['MTD'])
                    ) {

                        if (isset($planRules['ATRA'])) {
                            $debug[] = "Inside TRA block ";
                            $this->addToTargetReachedAccounts($account, $acntStartingBalance, $currentBalance, $currentEquity, $profitPercentage, $approvalCategoryId);
                            $debug[] = "Added to TRA. END";
                            continue;
                        } else {


                            // auto approve
                        }
                    }

                    if (isset($planRules['PSE'])) {

                        $controller->breachEvent($account, "Month End Partial Profit | Minimum Trading Days not fulfilled");
                    } else {
                        $subend = Helper::subend_days($account->duration);
                        $subscription = Subscription::create([
                            'account_id' => $account->id,
                            'login' => $account->login,
                            'plan_id' => $account->plan_id,
                            'ending_at' => $subend['string'],
                        ]);

                        //! Balance reset , in profit but not PT and not Partial
                        $controller->balanceReset($account->id);
                        $account->beforeLatestMetric->delete();
                        $account->latestMetric->delete();
                    }
                } else {

                    //!  Account in loss
                    $mtdFulfilled = true;
                    if (($accountPackage == Plan::EX_DEMO) || ($accountPackage == Plan::EX_REAL)) {

                        if (isset($planRules['MTD'])) {
                            if (($account->tradingDays()) < $planRules['MTD']['value']) {
                                $mtdFulfilled = false; // ! Minimum trading days condition not fulfilled
                            }
                        }


                        if (isset($planRules['PSE']) && (!$mtdFulfilled)) {

                            $controller->breachEvent($account, "Month Ended in Loss");
                        } else {
                            $subend = Helper::subend_days($account->duration);
                            $subscription = Subscription::create([
                                'account_id' => $account->id,
                                'login' => $account->login,
                                'plan_id' => $account->plan_id,
                                'ending_at' => $subend['string'],
                            ]);
                        }
                    } else {

                        if (isset($planRules['PSE'])) {

                            $controller->breachEvent($account, "Month Ended in Loss");
                        } else {
                            $subend = Helper::subend_days($account->duration);
                            $subscription = Subscription::create([
                                'account_id' => $account->id,
                                'login' => $account->login,
                                'plan_id' => $account->plan_id,
                                'ending_at' => $subend['string'],
                            ]);

                            // $account->beforeLatestMetric->delete();
                            // $account->latestMetric->delete();
                        }
                    }
                }
            } else {
                //! --------------------------- Sub not end today ------------------------------
                $debug[] = "Not same day!";

                if ($accountProfit > 0) {  //!Acocunt in profit
                    $debug[] = "Account in profit";
                    if (isset($planRules['PT'])) {

                        if (isset($planRules['AGF'])) {
                            // ! If account has GrowthFund add that also to profit amount

                            $growthFunds = $account->growthFund;
                            if ($growthFunds != null) {

                                $growthFundAmount = $growthFunds->sum('amount');
                                $accountProfit = $accountProfit + $growthFundAmount;
                            }
                        }
                        $profitPercentage = ($accountProfit / $acntStartingBalance) * 100;


                        if ($profitPercentage >= $planRules['PT']['value']) {
                            //!Target Reached
                            $approvalCategoryId = 1;  //! when full account target reached
                            if (isset($planRules['MTD'])) {
                                if (($account->tradingDays()) < $planRules['MTD']['value']) {
                                    $debug[] = "Minimum trading days : " . $account->tradingDays();
                                    $debug[] = "MTD not fulfilled";
                                    $targetFlag = false; // ! Minimum trading days condition not fulfilled
                                }
                            }
                            // if (isset($planRules['CR'])) { //TODO: CR to CRD when consistency is required
                            //     if (!($account->isConsistent($planRules['CRD']['value']))) {


                            //         $debug[] = "Not consistent";
                            //         $targetFlag = false; // ! is not consistent
                            //     }
                            // }
                            $runningTrades = Redis::smembers('orders:' .  $account->login . ':working');
                            if (!empty($runningTrades)) {
                                $debug[] = "Has Running Trades : " . json_encode($runningTrades, JSON_PRETTY_PRINT);
                                $targetFlag = false; //! Has running trades
                            }
                        } else {
                            //  ! Profit Target not fulfilled
                            $targetFlag = false;
                        }
                        $debug[] = $targetFlag ? "Target Flag : True" : "Target Flag : False";

                        if ($targetFlag) {

                            $this->profitTargetReached($account, $acntStartingBalance, $currentBalance, $currentEquity, $profitPercentage);
                            continue;
                        }
                    }
                } else {

                    $debug[] = "Account not in profit";

                    continue;
                }
            }

            Log::debug("Profit target log: ", [$debug]);
        }
        Helper::discordAlert("**Profit Checker Ended**" . Carbon::now());
    }

    public function accountProfitChecker(int $id, NewsService $newsService)
    {

        $debug = [];
        $account = Account::with('plan', 'customer')->findOrFail($id);
        $controller = new \App\Http\Controllers\AccountController();
        if ($account->breached) {
            Log::info("account already breached", [$account->login, $account->parent_account_id]);
            return response()->json(['message' => 'account already breached'], 400);
        }
        // $debug[] = "---------------------------------------------------------------------------------------------------";
        $accountPackage = $account->plan->type;
        if ($accountPackage == Plan::EV_P1) {
            ProfitCheckerJob::dispatch($account)->onQueue(AppConstants::QUEUE_PROFIT_CHECKER_JOB);
            return response()->json(['message' => 'reload'], 200);
        }
        $debug[] = "Account Id " . $account->id . " Login: " . $account->login;
        $targetFlag = true;
        $planRules = $account->planRules();


        $redisData = json_decode(Redis::get('margin:' . $account->login), 1);

        $currentBalance = $redisData['balance'];
        $currentEquity = $redisData['equity'];

        $accountCurrentBalance = $redisData['balance'];

        $acntStartingBalance = $account->starting_balance;
        $accountProfit = $currentBalance - $acntStartingBalance;
        $profitPercentage = ($accountProfit / $acntStartingBalance) * 100;

        $this->restrictedNews = $newsService->weeksNews(5);



        $endingDate = Carbon::parse($account->currentSubscription->ending_at);
        $now = Carbon::now();

        $debug[] = "Now " . $now . " End Date" . $endingDate;



        // ! Subscription ends today
        if ($now->gte($endingDate)) {

            $debug[] = "Subscription End today" . $endingDate . "Current Date" . $now;

            if ($accountProfit > 0) {  //!Acocunt in profit
                $debug[] = "In Profit";

                if (isset($planRules['PT'])) {

                    if (isset($planRules['AGF'])) {
                        // ! If account has GrowthFund add that also to profit amount

                        $growthFunds = $account->growthFund;
                        if ($growthFunds != null) {

                            $growthFundAmount = $growthFunds->sum('amount');
                            $accountProfit = $accountProfit + $growthFundAmount;
                        }
                    }
                    $debug[] = "Profit : $accountProfit";
                    $profitPercentage = ($accountProfit / $acntStartingBalance) * 100;

                    $debug[] = "Profit Percentage: " . $profitPercentage;
                    $debug[] = "Profit Target Percentage :" . $planRules['PT']['value'];
                    if ($profitPercentage >= $planRules['PT']['value']) {
                        //!Target Reached
                        $approvalCategoryId = 1;  //! when full account target reached
                        if (isset($planRules['MTD'])) {
                            if (($account->tradingDays()) < $planRules['MTD']['value']) {
                                $debug[] = "Minimum trading days : " . $account->tradingDays();
                                $debug[] = "MTD not fulfilled";
                                $targetFlag = false; // ! Minimum trading days condition not fulfilled
                            }
                        }
                        // if (isset($planRules['CR'])) { //TODO: CR to CRD when consistency is required
                        //     if (!($account->isConsistent($planRules['CRD']['value']))) {


                        //         $debug[] = "Not consistent";
                        //         $targetFlag = false; // ! is not consistent
                        //     }
                        // }
                        $runningTrades = Redis::smembers('orders:' .  $account->login . ':working');
                        if (!empty($runningTrades)) {
                            $debug[] = "Has Running Trades : " . json_encode($runningTrades, JSON_PRETTY_PRINT);
                            $targetFlag = false; //! Has running trades
                        }
                    } else {
                        //  ! Profit Target not fulfilled
                        $targetFlag = false;
                    }
                    $debug[] = $targetFlag ? "Target Flag : True" : "Target Flag : False";

                    if ($targetFlag) {

                        $this->profitTargetReached($account, $acntStartingBalance, $currentBalance, $currentEquity, $profitPercentage);
                        return $debug;
                    }
                }
                $approvalCategoryId = 2; //! Monthly partial profit
                $debug[] = "Account in partial profit";

                if ((isset($planRules['MTD']) && ($account->tradingDays() >= $planRules['MTD']['value'])) || !isset($planRules['MTD'])
                ) {

                    if (isset($planRules['ATRA'])) {
                        $debug[] = "Inside TRA block ";
                        $this->addToTargetReachedAccounts($account, $acntStartingBalance, $currentBalance, $currentEquity, $profitPercentage, $approvalCategoryId);
                        $debug[] = "Added to TRA. END";

                        return $debug;
                    } else {


                        // auto approve
                    }
                }

                if (isset($planRules['PSE'])) {

                    $controller->breachEvent($account, "Month End Partial Profit | Minimum Trading Days not fulfilled");
                } else {
                    $subend = Helper::subend_days($account->duration);
                    $subscription = Subscription::create([
                        'account_id' => $account->id,
                        'login' => $account->login,
                        'plan_id' => $account->plan_id,
                        'ending_at' => $subend['string'],
                    ]);
                    //! Balance reset , in profit but not PT and not Partial
                    $controller->balanceReset($account->id);
                    $account->beforeLatestMetric->delete();
                    $account->latestMetric->delete();
                }
            } else {
                //!  Account in loss
                $accountPackage = $account->plan->type;
                $mtdFulfilled = true;
                if (($accountPackage == Plan::EX_DEMO) || ($accountPackage == Plan::EX_REAL)) {
                    if (isset($planRules['MTD'])) {
                        if (($account->tradingDays()) < $planRules['MTD']['value']) {
                            $mtdFulfilled = false; // ! Minimum trading days condition not fulfilled
                        }
                    }
                    if (isset($planRules['PSE']) && (!$mtdFulfilled)) {

                        $controller->breachEvent($account, "Month Ended in Loss");
                    } else {
                        $subend = Helper::subend_days($account->duration);
                        $subscription = Subscription::create([
                            'account_id' => $account->id,
                            'login' => $account->login,
                            'plan_id' => $account->plan_id,
                            'ending_at' => $subend['string'],
                        ]);
                    }
                } else {
                    if (isset($planRules['PSE'])) {

                        $controller->breachEvent($account, "Month Ended in Loss");
                    } else {
                        $subend = Helper::subend_days($account->duration);
                        $subscription = Subscription::create([
                            'account_id' => $account->id,
                            'login' => $account->login,
                            'plan_id' => $account->plan_id,
                            'ending_at' => $subend['string'],
                        ]);

                        $account->beforeLatestMetric->delete();
                        $account->latestMetric->delete();
                    }
                }
            }
        } else {
            //! --------------------------- Sub not end today ------------------------------
            $debug[] = "Not same day!";

            if ($accountProfit > 0) {  //!Acocunt in profit
                $debug[] = "Account in profit";
                if (isset($planRules['PT'])) {

                    if (isset($planRules['AGF'])) {
                        // ! If account has GrowthFund add that also to profit amount

                        $growthFunds = $account->growthFund;
                        if ($growthFunds != null) {

                            $growthFundAmount = $growthFunds->sum('amount');
                            $accountProfit = $accountProfit + $growthFundAmount;
                        }
                    }
                    $profitPercentage = ($accountProfit / $acntStartingBalance) * 100;


                    if ($profitPercentage >= $planRules['PT']['value']) {
                        //!Target Reached
                        $approvalCategoryId = 1;  //! when full account target reached
                        if (isset($planRules['MTD'])) {
                            if (($account->tradingDays()) < $planRules['MTD']['value']) {
                                $debug[] = "Minimum trading days : " . $account->tradingDays();
                                $debug[] = "MTD not fulfilled";
                                $targetFlag = false; // ! Minimum trading days condition not fulfilled
                            }
                        }
                        // if (isset($planRules['CR'])) { //TODO: CR to CRD when consistency is required
                        //     if (!($account->isConsistent($planRules['CRD']['value']))) {


                        //         $debug[] = "Not consistent";
                        //         $targetFlag = false; // ! is not consistent
                        //     }
                        // }
                        $runningTrades = Redis::smembers('orders:' .  $account->login . ':working');
                        if (!empty($runningTrades)) {
                            $debug[] = "Has Running Trades : " . json_encode($runningTrades, JSON_PRETTY_PRINT);
                            $targetFlag = false; //! Has running trades
                        }
                    } else {
                        //  ! Profit Target not fulfilled
                        $targetFlag = false;
                    }
                    $debug[] = $targetFlag ? "Target Flag : True" : "Target Flag : False";

                    if ($targetFlag) {

                        $this->profitTargetReached($account, $acntStartingBalance, $currentBalance, $currentEquity, $profitPercentage);
                        return $debug;
                    }
                }
            } else {

                $debug[] = "Account not in profit";

                return $debug;
            }
            return $debug;
        }
    }



    public function profitTargetReached(Account $account, $acntStartingBalance, $currentBalance, $currentEquity, $profitPercentage)
    {

        $controller = new \App\Http\Controllers\AccountController();
        $approvalCategoryId = 1;

        if (isset($planRules['ANP'])) {
            // ! For automatic migrate
            $controller->planMigrate($account->id);
        } else {

            // ! Add account to TRA
            $this->addToTargetReachedAccounts($account, $acntStartingBalance, $currentBalance, $currentEquity, $profitPercentage, $approvalCategoryId);
        }
    }

    public function addToTargetReachedAccounts(Account $account, $acntStartingBalance, $currentBalance, $currentEquity, $profitPercentage, $approvalCategoryId)
    {
        if (isset($planRules['NCA'])) {
            $consistent = false;
        } else {
            if (isset($planRules['CRD'])) {  //TODO: CR to CRD when consistency is required
                $consistent = $account->isConsistent($planRules['CRD']['value']);
            } else $consistent = false;
        }

        $news = $this->restrictedNews;
        $accountService = $this->accountService;

        $newsTrades = $accountService->checkNewsTrades($news, $account);

        TargetReachedAccount::create(
            [
                'account_id' => $account->id,
                'approval_category_id' => $approvalCategoryId,
                //! target reach category
                'metric_info' => json_encode([
                    'balance' => $currentBalance,
                    'equity' => $currentEquity,
                    'starting_balance' => $acntStartingBalance,

                ]),
                'rules_reached' => json_encode([
                    'minimum_trading_days' => $account->tradingDays(),
                    'consistency_rule' => $consistent,
                    'profit_target' => $profitPercentage,
                    'news' => empty($newsTrades) ? json_encode([]) : json_encode(['news']),
                ]),
                'plan_id' => $account->plan_id,
                'subscription_id' => $account->subscription_id,
            ]
        );
        $approvalCategory = ApprovalCategory::find($approvalCategoryId);
        $controller = new \App\Http\Controllers\AccountController();
        $controller->breachEvent($account,  $approvalCategory->name);
        // kyc eligible mail
        // $this->kycEligibleMail($approvalCategory->id, $account, $consistent);

        // kyc eligible mail
        $this->kycEligibleMail($approvalCategory->id, $account, $consistent);
        //Eligible for payout on target reach mail
        // $this->EligibleForPayoutOnTargetReachedMail($account);
    }


    public function EligibleForPayoutOnTargetReachedMail($account)
    {
        $nextPlan = Plan::whereId($account->plan->next_plan)->first();
        if (($account->plan->type == Plan::EX_DEMO)) {

            $details = [
                'template_id'          => EmailConstants::ELIGIBLE_FOR_PAYOUT_ON_TRA_MAIL,
                'to_name'              => Helper::getOnlyCustomerName($account->customer->name),
                'to_email'             => $account->customer->email,
                'email_body' => [
                    "name" => Helper::getOnlyCustomerName($account->customer->name),
                    "login_id" => $account->login
                ]
            ];
            EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
        } elseif (($account->plan->type == Plan::EX_REAL) || ($account->plan->type == Plan::EV_REAL)) {
            $account = Account::with('customer', 'latestSubscription')->find($account->id);
            if (Carbon::createFromFormat('Y-m-d H:i:s', $account->latestSubscription->ending_at)->toDateString() <= Carbon::now()->toDateString()) {
                $details = [
                    'template_id'          => EmailConstants::ELIGIBLE_FOR_PAYOUT_ON_TRA_MAIL,
                    'to_name'              => Helper::getOnlyCustomerName($account->customer->name),
                    'to_email'             => $account->customer->email,
                    'email_body' => [
                        "name" => Helper::getOnlyCustomerName($account->customer->name),
                        "login_id" => $account->login
                    ]
                ];
                EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
            }
        }
    }


    public function tradeSyncDispatcher()
    {
        $accounts = Account::with('plan')->where('breached', 0)->get();
        $i = 0;
        Helper::discordAlert("**Trade Sync Dispatcher Running**");
        foreach ($accounts as $account) {
            TradeSync::dispatch($account)->onQueue(AppConstants::QUEUE_TRADE_SYNC_JOB);
        }
    }

    public function kycEligibleMail(int $approvalCategory_id, $account, bool $consistent)
    {
        if ($approvalCategory_id == Account::PROFIT_TARGET_REACHED_APPROVAL) {
            $nextPlan = Plan::whereId($account->plan->next_plan)->first();
            if ($nextPlan && $nextPlan->type == Plan::EX_REAL || $nextPlan->type == Plan::EV_REAL) {
                $realAccounts = Account::where('customer_id', $account->customer_id)->whereHas('plan', function ($q) use ($nextPlan) {
                    $q->whereIn('type', [Plan::EX_REAL, Plan::EV_REAL]);
                })->count();

                $kycDone = CustomerKycs::where('customer_id', $account->customer->id)->where('status', AppConstants::KYC_APPROVED)
                    ->where('approval_status', CustomerKycs::STATUS_ENABLE)->first();

                if ($realAccounts && $kycDone && ($account->plan->type == Plan::EX_DEMO || $account->plan->type == Plan::EV_P2)) {
                    $details = [
                        'template_id'          => EmailConstants::FORM_ELIGIBLE_MAIL,
                        'to_name'              => Helper::getOnlyCustomerName($account->customer->name),
                        'to_email'             => $account->customer->email,
                        'email_body' => [
                            "name" => Helper::getOnlyCustomerName($account->customer->name),
                            "login" => $account->login,
                        ]
                    ];
                    EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
                }

                // $consistent rule check disabled for now
                if (!$kycDone && ($account->plan->type == Plan::EX_DEMO || $account->plan->type == Plan::EV_P2)) {
                    $details = [
                        'template_id'          => EmailConstants::KYC_ELIGIBLE_MAIL,
                        'to_name'              => Helper::getOnlyCustomerName($account->customer->name),
                        'to_email'             => $account->customer->email,
                        'email_body' => [
                            "name" => Helper::getOnlyCustomerName($account->customer->name),
                        ]
                    ];
                    EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
                }
            }
        }
    }
}
