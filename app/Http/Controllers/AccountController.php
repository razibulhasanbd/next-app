<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use DateTime;
use Exception;
use Carbon\Carbon;
use Fernet\Fernet;
use App\Models\Plan;
use App\Helper\Helper;
use App\Jobs\EmailJob;
use App\Models\Account;
use App\Models\MtServer;
use App\Models\RuleName;
use App\Models\TopupLog;
use App\Models\GrowthFund;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\AccountMetric;
use Illuminate\Http\Response;
use App\Models\ExtendCycleLog;
use App\Services\TradeService;
use App\Services\AccountService;
use App\Constants\EmailConstants;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\TargetReachedAccount;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class AccountController extends Controller
{

    protected $accountService;

    public function __construct(AccountService $accountService = null)
    {
        $this->accountService = $accountService;
    }


    public function readOnly(int $account)
    {

        $account = Account::find($account);
        if ($account == null) {
            return response()->json(['message' => "This account doesn't exist"], 404);
        }

        $server = $account->server;
        $url = $server->url;
        $sessionToken = $server->login;

        $updateAccount = Http::acceptJson()->post($url . "/user/update?token=" . $sessionToken, [
            'login' => $account->login,
            'read_only' => 1,

        ]);
    }

    public function enable(int $account)
    {

        $account = Account::find($account);
        if ($account == null) {
            return response()->json(['message' => "This account doesn't exist"], 404);
        }

        $server = $account->server;
        $url = $server->url;
        $sessionToken = $server->login;

        $updateAccount = Http::acceptJson()->post($url . "/user/update?token=" . $sessionToken, [
            'login' => $account->login,
            'read_only' => 0,

        ]);
    }

    public static function accountReport(int $account)
    {

        $account = Account::find($account);

        if ($account == null) {
            return response()->json(['message' => "This account doesn't exist"], 404);
        }

        $server = $account->server;
        $url = $server->url;
        $sessionToken = $server->login;
        try {
            $userReport = Http::acceptJson()->timeout(8)->get($url . "/user/" . $account->login . "?token=" . $sessionToken);
            // $userReport = $userReport->successful() ? !(json_decode($userReport, 1)['read_only']) : 'error';
        } catch (Exception $e) {
            $status = 'error';



            return $status;
        }



        if ($userReport->successful()) {
            $response = json_decode($userReport, 1);
            $status = !$response['read_only'];
        } else {

            $status = 'error';
        }

        // Log::info(json_encode($status));
        return $status;
    }
    public function show(int | string $account)
    {

        if (($account == null) || (($account == 'null'))) {
            return;
        }

        $account = Account::find($account);

        if ($account == null) {
            return response()->json(['message' => "This account doesn't exist"], 404);
        }
        // $latestSubscription = $account->latestSubscription->toArray();

        $latestSubscription = Subscription::where('account_id', $account->id)->latest('created_at')->first();
        //  return  response()->json($latestSubscription);
        // $userAccount['trading_cycle']['starting_time']='';
        $userAccount['trading_cycle'] = [];

        $userAccount = $account->toArray();
        $userAccount['customer'] = $account->customer;
        $userAccount['metrics'] = $account->thisCycleMetrics;
        $userAccount['startingBalance'] = $account->starting_balance;
        $userAccount['serverId'] = $account->server->friendly_name;
        // return gettype($userAccount);
        $userAccount['trading_cycle']['starting_time'] = $latestSubscription->created_at;

        $datetimeFormat = 'Y-m-d H:i:s';

        $date = new \DateTime();
        $date->setTimestamp(strtotime($latestSubscription->ending_at . "+1 day"));
        $ending = $date->format($datetimeFormat);
        $userAccount['trading_cycle']['id'] = $latestSubscription->id;
        $userAccount['trading_cycle']['ending_time'] = $ending;

        return response()->json($userAccount);
    }

    public function allActive()
    {

        $accounts = Account::where('breached', 0)->get();

        return response()->json($accounts);
    }

    public function runningTrade(int $id)
    {

        $account = Account::findOrFail($id);
        $runningTrades = Redis::smembers('orders:' . $account->login . ':working');
        $debug = [];
        if (!empty($runningTrades)) {
            $debug[] = "Has Running Trades : " . json_encode($runningTrades, JSON_PRETTY_PRINT);
        }
        return json_encode($debug);
    }

    public function marginClear(int $id)
    {

        $account = Account::findOrFail($id);
        $response = Redis::del('margin:' . $account->login); //!Delete Redis Key
        $response = json_encode($response) == 1 ? "Margin Clear Successfully!" : "Something Went Wrong";

        return json_encode($response);
    }

    public function delSmembers(int $id)
    {

        $account = Account::findOrFail($id);

        $response = Redis::del('orders:' . $account->login . ':working');
        return json_encode($response);
    }

    public function totalRunningTradeCount()
    {
        $accounts = Account::all();
        $activeTrades = [];
        foreach ($accounts as $account) {
            $loginTrades = Redis::smembers('orders:' . $account->login . ':working');
            if ($loginTrades != null) {
                array_push($activeTrades, ...$loginTrades);
            } //!Make a long list of all active trades

        }
        return count($activeTrades);
    }

    public function delAllSmembers()
    {
        $accounts = Account::get();
        $response = [];
        foreach ($accounts as $account) {
            $response[$account->login] = Redis::del('orders:' . $account->login . ':working');
        }
        return json_encode($response);
    }

    public function breachEvent(Account $account, $breachedby)
    {

        try {
            $server = $account->server;
            $url = $server->url;
            $sessionToken = $server->login;

            $updateAccount = Http::acceptJson()->post($url . "/user/update?token=" . $sessionToken, [
                'login' => $account->login,
                'read_only' => 1,

            ]);

            $activeTrades = [];

            $login = $account->login;
            //!get all active trades for that account
            $loginTrades = Redis::smembers('orders:' . $login . ':working');

            if ($loginTrades != null) {
                // dd($loginTrades);
                array_push($activeTrades, ...$loginTrades);
            } //!Make a long list of all active trades

            //  dd($activeTrades);
            $activeTrades = array_map('intval', $activeTrades);

            $server = $account->server;
            $url = $server->url;
            $sessionToken = $server->login;

            //!get all trade reports for lots and price
            $tradesReport = Http::acceptJson()->post($url . "/trades/report?token=" . $sessionToken, [

                'orders' => $activeTrades,
            ]);


            if (isset($tradesReport['data'])) {
                $tradesReport = $tradesReport['data'];

                // return $tradesReport;
                //!close all trades with the reports one by one
                foreach ($tradesReport as $trade) {
                    //  dd($trade);

                    if ($trade['type'] >= 2 && $trade['type'] <= 5) {
                        $tradeClose = Http::acceptJson()->post($url . "/trades/cancel?token=" . $sessionToken, [
                            'ticket' => $trade['ticket'],
                        ]);
                    } else {

                        $tradeClose = Http::acceptJson()->post($url . "/trades/close?token=" . $sessionToken, [
                            'ticket' => $trade['ticket'],
                            'lots' => $trade['volume'],
                            'price' => $trade['close_price'],
                        ]);
                    }

                    // return $tradeClose;
                }
            } else {

                Helper::discordAlert("**Trade Fetch Error**:\nAccntID : " . $account->id . "\nLogin : " . $login . "\nTradeReportResponse: " . json_encode($tradesReport));
            }
        } catch (\Exception $e) {
            Helper::discordAlert("**Breach Event Error**:\nAccntID : " . $account->id . "\nLogin : " . $login . "\nError: " . json_encode($e));

            throw $e;
        }
        // return $customer;
        $url = $account->server->url;
        if ($updateAccount->successful()) {

            $redisData = json_decode(Redis::get('margin:' . $account->login), 1);
            if ($redisData != null) {

                $currentBalance = $redisData['balance'];
                $currentEquity = $redisData['equity'];
            } else {

                $redisDataFromApi = Http::get($url . "/user/margin/" . $account->login . "?token=" . $sessionToken);
                $redisData = json_decode($redisDataFromApi, 1);
                $currentBalance = $redisData['balance'];
                $currentEquity = $redisData['equity'];
                Redis::set('margin:' . $account->login, json_encode(['balance' => $currentBalance, 'equity' => $currentEquity]));
            }

            $account->balance = $currentBalance;
            $account->equity = $currentEquity;

            $account->latestMetric->lastBalance = $currentBalance;
            $account->latestMetric->lastEquity = $currentEquity;

            $account->breached = true;
            $login = $account->login;
            $account->breachedby = $breachedby;
            $account->unsetRelation('plan');
            $account->unsetRelation('accountRules');

            // Log::info(json_encode($account, JSON_PRETTY_PRINT));
            $account->push();

            Helper::discordAlert("**Breach Event**:\nAccntID : " . $account->id . "\nLogin : " . $login . "\nBreached By : " . $breachedby . "\nMetricID : " . $account->latestMetric->id);
            // $planRules = $account->planRules();

            $notifyBreach = Http::withHeaders([
                'Accept' => 'application/json',
                'X-Verification-Key' => env('WEBHOOK_TOKEN'),
            ])->post(env('FRONTEND_URL') . "/api/v1/webhook/rule-breaches", [

                "accountHistoryId" => $account->latestMetric->id,
                "accountId" => $account->id,
                "type" => $breachedby,
                "message" => "The account $login is paused due to : $breachedby ",
                "pushed" => 0,
                "Breached" => true,
                "utcTime" => gmdate("Y-m-d H:i:s"),
                "inserted" => gmdate("Y-m-d H:i:s"),
                "updated" => gmdate("Y-m-d H:i:s"),

            ]);
            if (($account->plan->type == Plan::EV_P1 && ($account->breachedby == 'Daily Loss Limit' || $account->breachedby == 'Month Ended in Loss' || $account->breachedby == 'Month End Partial Profit | Minimum Trading Days not fulfilled')) || ($account->plan->type == Plan::EX_DEMO && ($account->breachedby == 'Daily Loss Limit' || $account->breachedby == 'Month Ended in Loss'))) {
                $details = [
                    'template_id'          => EmailConstants::USER_BREACHED_SEND_RESET_OPTION,
                    'to_name'              => Helper::getOnlyCustomerName($account->customer->name),
                    'to_email'             => $account->customer->email,
                    'email_body' => [
                        "name" => Helper::getOnlyCustomerName($account->customer->name),
                        "login_id" => $account->login,
                        "discount_percentage" => ($account->plan->type == Plan::EX_DEMO ? '20%' : '10%')
                    ]
                ];
                EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
            }

            return $updateAccount['message'];
        } else {
            Helper::discordAlert("**Could not make account Read-only**:\nAccntID : " . $account->id . "\nLogin : " . $login . "\nResponse: " . json_encode($updateAccount));
            return "error";
        }
    }

    public function endpointbydate(int $accountID, string $date)
    {

        $account = Account::findOrFail($accountID);
        $str = $date;
        $str = $str[6] . $str[7] . '-' . $str[4] . $str[5] . '-' . substr($str, 0, 4);

        $metricDate = date('Y-m-d', strtotime($str));
        // dd(  $metricDate);

        $specificMetric = AccountMetric::whereDate('metricDate', '=', $metricDate)
            ->where('account_id', '=', $accountID)->latest()->first();

        if ($specificMetric == null) {
            unset($account->password);
            return response()->json(["message" => "metric doesn't exist for this date", 'account' => $account], 404);
        }
        $previousDayMetric = $account->specificLastDayMetric($metricDate);
        if ($previousDayMetric == null) {
            unset($account->password);
            return response()->json(["message" => "metric doesn't exist for this date", 'account' => $account], 404);
        }
        // return $previousDayMetric;
        $accountSubStart = gmdate('Y-m-d', strtotime($account->currentSubscription->created_at));

        $uptoMetric = AccountMetric::whereDate('metricDate', '<=', $metricDate)->whereDate('metricDate', '>', $accountSubStart)
            ->where('account_id', '=', $accountID)->get();
        $subsStart = $account->latestSubscription->created_at;

        $trades_cycle = $account->uptoDateTrades($metricDate);

        // return $trades_cycle;
        // return Trade::where('account_id', '=', $accountID)->where('created_at', '>=', $account->latestSubscription->created_at)->whereDate('updated_at', '<=', $date)->get();
        // // return $account->latestSubscription;
        //  return $trades_cycle;
        if ($trades_cycle != null) {
            $losing_trades = $trades_cycle->where('profit', '<', 0)->where('close_time', '!=', 0);
            $winning_trades = $trades_cycle->where('profit', '>=', 0)->where('close_time', '!=', 0);
        }
        $url = $account->server->url;
        $redisData = json_decode(Redis::get('margin:' . $account->login), 1);
        if ($redisData != null) {
            $currentBalance = $redisData['balance'];
            $currentEquity = $redisData['equity'];
        } else {
            $server = $account->server;
            $url = $server->url;
            $sessionToken = $server->login;

            $redisDataFromApi = Http::get($url . "/user/margin/" . $account->login . "?token=" . $sessionToken);
            $redisData = json_decode($redisDataFromApi, 1);
            $currentBalance = $redisData['balance'];
            $currentEquity = $redisData['equity'];
        }

        $response = [

            'accountId' => $account->id,
            'login' => $account->login,
            'breached' => false,
            'breachedBy' => $account->breachedby,
            'maxDailyLoss' => $specificMetric->maxDailyLoss, //! specific date
            'maxMonthlyLoss' => $uptoMetric->min('maxMonthlyLoss'), //! upto that date
            'drawdown' => $currentBalance - $currentEquity,
            'activeTradingDay' => $uptoMetric->where('isActiveTradingDay', 1)->count(), //! upto that date
            'trades' => $trades_cycle->count(), //! upto that date
            'accountMaxDrawdown' => abs($uptoMetric->min('maxDailyLoss')), //! upto that date
            'averageLosingTrade' => round($losing_trades->avg('profit') != null ? $losing_trades->avg('profit') : 0, 2), //! upto that date
            'averageWinningTrade' => round($winning_trades->avg('profit') != null ? $winning_trades->avg('profit') : 0, 2), //! upto that date
            'winRate' => null,
            'currentBalance' => $currentBalance,
            'currentEquity' => $currentEquity,
            'currentRisk' => ($currentBalance != 0) ? $currentEquity / $currentBalance * 100 : 0,
            'daysSinceInitialDeposit' => (new DateTime($subsStart))->diff(new DateTime($metricDate))->days,
            'startingBalance' => $account->starting_balance,
            'thisMonthPnL' => $trades_cycle->sum('profit'), //! upto that date
            'toDatePnL' => $account->trades->sum('profit'), //! upto that date
            'todaysStartBalance' => $previousDayMetric->lastBalance, //! specific date
            'todaysStartEquity' => $previousDayMetric->lastEquity, //! specific date
            'lots' => $trades_cycle->sum('volume'),
            'updatedUTC' => $previousDayMetric->updated_at,
            'inserted' => $previousDayMetric->created_at,
            'updated' => $previousDayMetric->updated_at,
            'accumulatedProfit' => null,
            'availableRisk' => null,
            'accumulatedProfit' => null,
            'currentGrowth' => null,
            'dailyLossLimit' => null,
            'highWaterMark' => null,
            'lossRate' => null,
            'maxPl' => null,
            'maxWithdrawal' => null,
            'openPnL' => ($currentEquity - $currentBalance) > 0 ? ($currentEquity - $currentBalance) : 0,
            'priorMonthEquity' => null,
            'priorWeekBalance' => null,
            'priorWeekCash' => null,
            'priorWeekEquity' => null,
            'profitShare' => null,
            'projectedAnnualPnL' => null,
            'rateOfReturn' => null,
            'romad' => null,
            'sharpeRatio' => null,
            'thisMonthCash' => null,
            'thisWeekCash' => null,
            'toDateCash' => null,
            'topDayBalance' => null,
            'topDayEquity' => null,
            'topDayPnL' => null,
            'totalVolume' => null,
            'weeklyPnL' => null,
            'weeklyStartBalance' => null,
            'pushed' => null,
            'winningDays' => null,
            'maxLoss' => null,
            'currentDailyDrawdown' => null,
            'maxCurrentDailyDrawdown' => null,

        ];

        return $response;
    }

    public function test(int $accountID)
    {

        $account = Account::find($accountID);

        if ($account == null) {
            return response()->json(['message' => "Account-id $accountID doesn't exist"], 404);
        }

        if ($account->breachedby != null) {
            return response()->json([
                "accountId" => $account->id,
                "login" => $account->login,
                "breached" => true,
                "breachedBy" => $account->breachedby,
                "lastMetricBeforeBreaching" => $account->latestMetric,
            ]);
        }

        $subStart = $account->currentSubscription->created_at->format('M');

        return $account->thisCycleTrades;
    }

    public function endpoint(int $accountID)
    {

        $account = Account::with('latestSubscription', 'latestTwoMetrics', 'plan')->find($accountID);
        $account->latestMetric = $account->latestTwoMetrics[1] ?? null;
        $account->lastDayMetric = $account->latestTwoMetrics[0] ?? null;
        $account->beforeLatestMetric = $account->latestTwoMetrics[0] ?? null;
        // return $account;
        if ($account == null) {
            return response()->json(['message' => "Account-id $accountID doesn't exist"], 404);
        }

        // $controller = new \App\Http\Controllers\Admin\ExtendCycleLogController();
        // $cycleExtend =  $controller->checkCycleExtension($accountID);
        // return $thisMonthMetric->max('maxDailyLoss');
        $today = Carbon::today();
        $server = $account->plan->server;
        $url = $server->url;
        $sessionToken = $server->login;
        $redisData = json_decode(Redis::get('margin:' . $account->login), 1);
        if ($redisData != null) {
            $currentBalance = $redisData['balance'];
            $currentEquity = $redisData['equity'];
        } else {
            //return response()->json(['Error' => "Couldn't connect to redis"]);

            if (isset($sessionToken['error'])) {
                // return $sessionToken['error'];
                return response()->json(['message' => 'MT4 Server timeout'], 500);
            }
            $redisDataFromApi = Http::get($url . "/user/margin/" . $account->login . "?token=" . $sessionToken);
            $redisData = json_decode($redisDataFromApi, 1);
            $currentBalance = $redisData['balance'];
            $currentEquity = $redisData['equity'];
            Redis::set('margin:' . $account->login, json_encode(['balance' => $currentBalance, 'equity' => $currentEquity]));
        }
        $thisMonthMetric = $account->thisCycleMetrics;
        if ($thisMonthMetric == null) {
            return response()->json(['message' => "Account Metric for : $accountID doesn't exist"], 404);
        }

        $subsEnd = $account->latestSubscription->ending_at;
        $subsStart = $account->latestSubscription->created_at;

        $trades_cycle = $account->thisCycleTrades;
        if ($trades_cycle != null) {
            $losing_trades = $trades_cycle->where('profit', '<', 0)->where('close_time', '!=', 0);
            $winning_trades = $trades_cycle->where('profit', '>=', 0)->where('close_time', '!=', 0);
            $losing_trades_count = $losing_trades->count();
            $winning_trades_count = $winning_trades->count();
            $totalTrades = $trades_cycle->count();
        }

        $planRules = $account->planRules();
        if (isset($planRules['PT'])) {

            $profitTarget = ($account->starting_balance) * ($planRules['PT']['value'] / 100);
            if (isset($planRules['AGF'])) {
                $growthFundAmount = $account->growthFund->sum('amount');
                $profitTargetReached = (($currentBalance > ($account->starting_balance)) ? $currentBalance - ($account->starting_balance) : 0) + $growthFundAmount;
            } else {

                $profitTargetReached = ($currentBalance > ($account->starting_balance)) ? $currentBalance - ($account->starting_balance) : 0;
            }
        } else {
            $profitTarget = 0;
            $profitTargetReached = 0;
        }

        if (isset($planRules['DLL'])) {
            $lastDayBalance = isset($account->beforeLatestMetric) ? $account->beforeLatestMetric->lastBalance : 0;
            $dailyLossThreshold = ($planRules['DLL']['value'] / 100 * $account->starting_balance) - ($lastDayBalance - $currentEquity);
        } else {

            $dailyLossThreshold = null;
        }

        if (isset($planRules['MLL'])) {
            $maxLossThreshold = $currentEquity - ((1 - ($planRules['MLL']['value']) / 100) * $account->starting_balance);
        } else {
            $maxLossThreshold = null;
        }


        if ($account->breachedby != null) {

            return response()->json([
                "accountId" => $account->id,
                "login" => $account->login,
                "breached" => true,
                "breachedBy" => $account->breachedby,
                "lastMetricBeforeBreaching" => $account->latestMetric ?? null,
                'startingBalance' => $account->starting_balance,
                'maxDailyLoss' => isset($account->latestMetric) ? $account->latestMetric->maxDailyLoss : 0, //TODO last month metric before breaching
                'maxMonthlyLoss' => $thisMonthMetric->min('maxMonthlyLoss'), //TODO last month metric before breaching
                'dailyLossThreshold' => round($dailyLossThreshold, 2),
                'maxLossThreshold' => round($maxLossThreshold, 2),
                'currentBalance' => $currentBalance,
                'currentEquity' => $currentEquity,
                'drawdown' => ($currentEquity - $currentBalance) < 0 ? ($currentEquity - $currentBalance) : 0,
                'activeTradingDay' => $thisMonthMetric->where('isActiveTradingDay', 1)->count(), //TODO last month metric before breaching
                'trades' => $trades_cycle->count(),
                'daysSinceInitialDeposit' => (new DateTime($subsStart))->diff(new DateTime($today))->days, //TODO last month metric before breaching
                'accountMaxDrawdown' => abs($thisMonthMetric->min('maxDailyLoss')), // //TODO last month metric before breaching
                //'averageLosingTrade' => ($totalTrades != 0) ? round((($losing_trades_count / $totalTrades) * 100), 2) . "%" : 0,
                //'averageWinningTrade' => ($totalTrades != 0) ? round((($winning_trades_count / $totalTrades) * 100), 2) . "%" : 0,
                'averageLosingTrade' => round($losing_trades->avg('profit') != null ? $losing_trades->avg('profit') : 0, 2), //! upto that date
                'averageWinningTrade' => round($winning_trades->avg('profit') != null ? $winning_trades->avg('profit') : 0, 2), //! upto that date

                'winRate' => ($totalTrades != 0) ? round((($winning_trades_count / $totalTrades) * 100), 2) : 0,

                'currentRisk' => ($currentBalance != 0) ? $currentEquity / $currentBalance * 100 : 0,
                'thisMonthPnL' => $trades_cycle->sum('profit'), //! upto that date
                'toDatePnL' => $account->trades->sum('profit'), //! upto that date
                'todaysStartBalance' => $account->latestMetric->lastBalance ?? 0, //! specific date
                'todaysStartEquity' => $account->latestMetric->lastEquity ?? 0, //! specific date
                'lots' => $trades_cycle->sum('volume') / 100,
                'openPnL' => ($currentEquity - $currentBalance) > 0 ? ($currentEquity - $currentBalance) : 0,
                'currentProfitTarget' => ($currentBalance - $account->starting_balance) > 0 ? ($currentBalance - $account->starting_balance) : 0,
                'profitTarget' => $profitTarget,
                'profitTargetReached' => $profitTargetReached,

                'updatedUTC' => null,
                'inserted' => null,
                'updated' => null,
                'accumulatedProfit' => null,
                'availableRisk' => null,
                'accumulatedProfit' => null,
                'currentGrowth' => null,
                'dailyLossLimit' => null,
                'highWaterMark' => null,
                'lossRate' => null,
                'maxPl' => null,
                'maxWithdrawal' => null,
                'priorMonthEquity' => null,
                'priorWeekBalance' => null,
                'priorWeekCash' => null,
                'priorWeekEquity' => null,
                'profitShare' => null,
                'projectedAnnualPnL' => null,
                'rateOfReturn' => null,
                'romad' => null,
                'sharpeRatio' => null,
                'thisMonthCash' => null,
                'thisWeekCash' => null,
                'toDateCash' => null,
                'topDayBalance' => null,
                'topDayEquity' => null,
                'topDayPnL' => null,
                'totalVolume' => null,
                'weeklyPnL' => null,
                'weeklyStartBalance' => null,

                'pushed' => null,
                'winningDays' => null,
                'maxLoss' => null,
                'currentDailyDrawdown' => null,
                'maxCurrentDailyDrawdown' => null,
                // 'cycleExtend' => $cycleExtend
            ]);
        }

        $response = [

            'accountId' => $account->id,
            'login' => $account->login,
            'breached' => ($account->breached == 1) ? true : false,
            'breachedBy' => $account->breachedby,
            'startingBalance' => $account->starting_balance,
            'maxDailyLoss' => isset($account->latestMetric) ? $account->latestMetric->maxDailyLoss : 0, //! specific date
            'maxMonthlyLoss' => $thisMonthMetric->min('maxMonthlyLoss'), //! upto that date
            'dailyLossThreshold' => round($dailyLossThreshold, 2),
            'maxLossThreshold' => round($maxLossThreshold, 2),
            'currentBalance' => $currentBalance,
            'currentEquity' => $currentEquity,
            'drawdown' => ($currentEquity - $currentBalance) < 0 ? ($currentEquity - $currentBalance) : 0,
            'activeTradingDay' => $thisMonthMetric->where('isActiveTradingDay', 1)->count(), //! upto that date
            'trades' => $trades_cycle->count(), //! upto that date
            'daysSinceInitialDeposit' => (new DateTime($subsStart))->diff(new DateTime($today))->days,
            'accountMaxDrawdown' => abs($thisMonthMetric->min('maxDailyLoss')), //! upto that date
            //'averageLosingTrade' => ($totalTrades != 0) ? round((($losing_trades_count / $totalTrades) * 100), 2) . "%" : 0, //! Problem
            //'averageWinningTrade' => ($totalTrades != 0) ? round((($winning_trades_count / $totalTrades) * 100), 2) . "%" : 0, //! Problem
            'currentRisk' => ($currentBalance != 0) ? $currentEquity / $currentBalance * 100 : 0,
            'thisMonthPnL' => $trades_cycle->sum('profit'), //! upto that date
            'toDatePnL' => $account->trades->sum('profit'), //! upto that date
            'todaysStartBalance' => $account->lastDayMetric ? $account->lastDayMetric->lastBalance : $account->starting_balance, //! specific date
            'todaysStartEquity' => $account->lastDayMetric ? $account->lastDayMetric->lastEquity : $account->starting_balance, //! specific date
            'lots' => $trades_cycle->sum('volume') / 100,
            'openPnL' => $currentEquity - $currentBalance,

            'averageLosingTrade' => round($losing_trades->avg('profit') != null ? $losing_trades->avg('profit') : 0, 2), //! upto that date
            'averageWinningTrade' => round($winning_trades->avg('profit') != null ? $winning_trades->avg('profit') : 0, 2), //! upto that date
            'winRate' => ($totalTrades != 0) ? round((($winning_trades_count / $totalTrades) * 100), 2) : 0,
            'profitTarget' => $profitTarget,
            'profitTargetReached' => $profitTargetReached,
            'updatedUTC' => $account->lastDayMetric ? $account->lastDayMetric->updated_at : '0-0-0',
            'inserted' => $account->lastDayMetric ? $account->lastDayMetric->created_at : '0-0-0',
            'updated' => $account->lastDayMetric ? $account->lastDayMetric->updated_at : '0-0-0',
            'accumulatedProfit' => null,
            'availableRisk' => null,
            'accumulatedProfit' => null,
            'currentGrowth' => null,
            'dailyLossLimit' => null,
            'highWaterMark' => null,
            'lossRate' => null,
            'maxPl' => null,
            'maxWithdrawal' => null,
            'priorMonthEquity' => null,
            'priorWeekBalance' => null,
            'priorWeekCash' => null,
            'priorWeekEquity' => null,
            'profitShare' => null,
            'projectedAnnualPnL' => null,
            'rateOfReturn' => null,
            'romad' => null,
            'sharpeRatio' => null,
            'thisMonthCash' => null,
            'thisWeekCash' => null,
            'toDateCash' => null,
            'topDayBalance' => null,
            'topDayEquity' => null,
            'topDayPnL' => null,
            'totalVolume' => null,
            'weeklyPnL' => null,
            'weeklyStartBalance' => null,
            'pushed' => null,
            'winningDays' => null,
            'maxLoss' => null,
            'currentDailyDrawdown' => null,
            'maxCurrentDailyDrawdown' => null,
            // 'cycleExtend' => $cycleExtend
        ];

        return response()->json($response);
    }

    public function createMetric(int $id)
    {

        $account = Account::findOrFail($id);

        $today_trades = $account->todayTrades;

        $redisData = json_decode(Redis::get('margin:' . $account->login), 1);
        $currentBalance = $redisData['balance'];
        $currentEquity = $redisData['equity'];

        $activeTrades = Redis::smembers('orders:' . $account->login . ':working');

        $metric = AccountMetric::create([

            "accountId" => $id,
            "maxDailyLoss" => 0,
            "maxMonthlyLoss" => 0,
            "metricDate" => Carbon::now(),
            "isActiveTradingDay" => (count($activeTrades) > 0) ? true : false,
            "trades" => $account->trades()->whereDate('updated_at', Carbon::now())->count(),
            "averageLosingTrade" => $today_trades->avg('profit'),
            "averageWinningTrade" => $today_trades->avg('profit'),
            "lastBalance" => $currentBalance,
            "lastEquity" => $currentEquity,
            "lastRisk" => $currentEquity / $currentBalance * 100,

        ]);
    }

    public function topup(Request $request)
    {

        try {
            $account = Account::with('plan')->whereId($request['account_id'])->first();
            // return $account;
            // dd($account);
            // return $account->starting_balance;
            $accountStartingBalance = $account->starting_balance;

            // $currentEquity = 23000;
            // $currentBalance = 23000;

            $server = $account->server;
            $url = $server->url;
            $sessionToken = $server->login;
            $redisDataFromApi = Http::get($url . "/user/margin/" . $account->login . "?token=" . $sessionToken);
            $redisData = json_decode($redisDataFromApi, 1);
            $currentBalance = $redisData['balance'];
            $currentEquity = $redisData['equity'];

            if ($accountStartingBalance <= $currentBalance) {

                return ['error' => 'Account current balance is greater than starting balance'];
            }

            $deposit = Http::acceptJson()->post($url . "/user/deposit?token=" . $sessionToken, [

                'login' => $account->login,
                'amount' => $accountStartingBalance - $currentBalance,
                "is_credit" => false,
                "comment" => "Deposit-Topup",
                "check_free_margin" => false,
            ]);

            if (json_decode($deposit, 1)['code'] != 200) {
                return ['error' => 'Could not deposit to account!'];
            }

            $updateAccount = Http::acceptJson()->post($url . "/user/update?token=" . $sessionToken, [
                'login' => $account->login,
                'read_only' => 0,

            ]);
            if (json_decode($updateAccount, 1)['code'] != 200) {
                return ['error' => 'Could not enable account!'];
            }

            $account->breached = false;
            $account->breachedby = null;
            $account->balance = $accountStartingBalance;
            $account->equity = $accountStartingBalance;

            $last_metric = $account->beforeLatestMetric->toJson();
            $breachMetric = $account->latestMetric->toJson();

            //!create topup log
            $saveTopupLog = TopupLog::create(
                [
                    'account_id' => $account->id,
                    'last_metric' => $last_metric,
                    'breach_metric' => $breachMetric,
                    'topup_amount' => $accountStartingBalance - $currentBalance,
                ]
            );

            //!delete old metrics
            $account->beforeLatestMetric->delete();
            $account->latestMetric->delete();
            $account->push();
        } catch (\Exception $e) {

            throw $e;
            return response()->json(['message' => $e], 404);
        }

        return response()->json([
            'message' => 'Account Topup successful',
            'topup_amount' => $accountStartingBalance - $currentBalance,
        ], 200);
    }
    public function testLoop()
    {
        // Will return an array of files in the directory
        // Each array element is Symfony\Component\Finder\SplFileInfo object
        $files_data[] = File::get(storage_path('data_sample.txt'));
        $newArray = array();

        foreach ($files_data as $filedata) {

            // return $newArray = $filedata->post_title;

        }
    }

    public function breachEvents($id)
    {
        $account = Account::find($id);
        $breachEventDetails = $account->breachEventsForApi;

        if (isset($breachEventDetails['metrics'])) {
            $metrics = json_decode($breachEventDetails['metrics'], 1);
            // return $metrics;
            $breachEventDetails['metrics'] = json_encode(
                [
                    'maxDailyLoss' => $metrics['maxDailyLoss'],
                    'maxMonthlyLoss' => $metrics['maxMonthlyLoss'],
                    'lastBalance' => $metrics['lastBalance'],
                    'lastEquity' => $metrics['lastEquity'],
                ]

            );
        }

        $breachEventDetails['breacheBy'] = $account->breachedby;
        return response()->json(
            [
                $breachEventDetails,
            ]
        );
    }

    public function accountPlanRules($id)
    {
        $account = Account::find($id);

        return $account->planRules();

        //return  array_column($getArray, 'condition');;
    }

    public function balanceReset($accountID)
    {
        $account = Account::find($accountID);

        // return $accounts;

        $server = $account->server;
        $url = $server->url;
        $sessionToken = $server->login;

        $redisData = json_decode(Redis::get('margin:' . $account->login), 1);
        if ($redisData != null) {
            $currentBalance = $redisData['balance'];
            $currentEquity = $redisData['equity'];
        } else {
            $redisDataFromApi = Http::get($url . "/user/margin/" . $account->login . "?token=" . $sessionToken);
            $redisData = json_decode($redisDataFromApi, 1);
            $currentBalance = $redisData['balance'];
            $currentEquity = $redisData['equity'];
            Redis::set('margin:' . $account->login, json_encode(['balance' => $currentBalance, 'equity' => $currentEquity]));
        }
        $accountCurrentBalance = $redisData['balance'];

        $acntStartingBalance = $account->starting_balance;

        if ($accountCurrentBalance > $acntStartingBalance) {
            DB::beginTransaction();
            $account->balance = $acntStartingBalance;
            //!MT4 Update Withdraw

            try {
                $withdraw = Http::acceptJson()->post($url . "/user/withdraw?token=" . $sessionToken, [

                    'login' => $account->login,
                    'amount' => $accountCurrentBalance - $acntStartingBalance,
                    "is_credit" => false,
                    "comment" => "Withdraw",
                    "check_free_margin" => false,
                ]);
                $account->save();
                Redis::del('margin:' . $account->login);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong
            }
        } else {

            return "Account not in profit";
        }
    }

    public function planMigrate($id)
    {

        $account = Account::with('plan', 'customer')->find($id);
        $serverType = Plan::with('server')->whereId($account->plan->next_plan)->first();

        // try {
        // ! Create new account if real
        if ($account->plan->new_account_on_next_plan == 1) {

            //!Create new account JL Dashboard
            $phaseMigration = Http::withHeaders([
                'Accept' => 'application/json',
                'X-Verification-Key' => env('WEBHOOK_TOKEN'),
            ])->post(env('FRONTEND_URL') . "/api/v1/webhook/account-phase-migration", [
                "accountId" => $account->id,
                "phaseId" => null,
                "createNewAccount" => true,
            ]);

            // AccountPlanMigrationJob::dispatch($account)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);

            return true;
        } else {
            try {
                DB::beginTransaction();
                // ! Migrate Plan
                if ($account == null) {
                    return response()->json(['message' => "This account doesn't exist"], 404);
                }

                $phaseMigration = Http::withHeaders([
                    'Accept' => 'application/json',
                    'X-Verification-Key' => env('WEBHOOK_TOKEN'),
                ])->post(env('FRONTEND_URL') . "/api/v1/webhook/account-phase-migration", [
                    "accountId" => $account->id,
                    "phaseId" => null,
                    "createNewAccount" => false,
                ]);

                $server = $account->server;
                $url = $server->url;
                $sessionToken = $server->login;
                $nextPlan = Plan::find($account->plan->next_plan);
                $updateAccount = Http::acceptJson()->post($url . "/user/update?token=" . $sessionToken, [
                    'login' => $account->login,
                    'read_only' => 0,
                ]);
                if (json_decode($updateAccount, 1)['code'] == 200) {

                    $oldmetric = AccountMetric::create([

                        "account_id" => $account->id,
                        "maxDailyLoss" => 0,
                        "maxMonthlyLoss" => 0,
                        "metricDate" => Carbon::yesterday(),
                        "isActiveTradingDay" => false,
                        "trades" => 0,
                        "averageLosingTrade" => 0,
                        "averageWinningTrade" => 0,
                        "lastBalance" => $nextPlan->startingBalance,
                        "lastEquity" => $nextPlan->startingBalance,
                        "lastRisk" => 0,

                    ]);

                    $todayMetric = AccountMetric::create([
                        "account_id" => $account->id,
                        "maxDailyLoss" => 0,
                        "maxMonthlyLoss" => 0,
                        "metricDate" => Carbon::today(),
                        "isActiveTradingDay" => false,
                        "trades" => 0,
                        "averageLosingTrade" => 0,
                        "averageWinningTrade" => 0,
                        "lastBalance" => $nextPlan->startingBalance,
                        "lastEquity" => $nextPlan->startingBalance,
                        "lastRisk" => 0,

                    ]);

                    $subend = Helper::subend_days($nextPlan->duration);
                    $subscription = Subscription::create([
                        'account_id' => $account->id,
                        'login' => $account->login,
                        'plan_id' => $nextPlan->id,
                        'ending_at' => $subend['string'],
                    ]);
                    $account->plan_id = $nextPlan->id;
                    $account->save();
                    DB::commit();
                    $this->balanceReset($account->id);
                }
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
            //call Reset Balance

        }
    }

    public function monthEndDebug()
    {
        $accounts = Account::with('latestSubscription')->where('breached', '!=', true)->get();

        $filtered = $accounts->filter(function ($account) {
            if ($account->latestSubscription->ending_at <= Carbon::now()) {
                return true;
            }
        });
        $filtered = $filtered->flatten(1);

        $subs = $filtered->pluck('latestSubscription');

        foreach ($subs as $sub) {

            $s = Subscription::whereId($sub->id)->update(['ending_at' => Carbon::now()]);
        }

        return $filtered->values()->all();
    }

    public function closeRunningTrades($id)
    {

        $account = Account::find($id);

        $close = $account->closeRunningTrades();
        return $close;
    }
    public function debug()
    {
        // $accounts = Account::with('plan')->where('breached', true)->take(1)->get();
        $accounts = Account::where('breached', true)->paginate(150);

        $debug = [];
        foreach ($accounts as $account) {
            $server = $account->server;
            $url = $server->url;
            $sessionToken = $server->login;

            $userReport = Http::acceptJson()->get($url . "/user/" . $account->login . "?token=" . $sessionToken);
            $userReport = json_decode($userReport, 1);
            if (!isset($userReport['read_only'])) {
                continue;
            }
            $accountStatus = !$userReport['read_only'];
            $runningTrades = Redis::smembers('orders:' . $account->login . ':working');
            $debug[$account->login]['status'] = $accountStatus ? "on" : "off";
            $debug[$account->login]['trades'] = json_encode($runningTrades);
        }

        return $debug;
    }

    public function topupAccount(Request $request)
    {
        $accountID = $request->input('account_id');
        $account = Account::with('plan','accountRules')->find($accountID);
        $accountInTra = TargetReachedAccount::where('account_id',$accountID)->first();
        if(isset($accountInTra)){
            return response()->json([
                "success" => false,
                "code" => 400,
                "message" => "Account in profit target reach"
            ]);
        }

        if ($account == null) {
            //!Error
            return response()->json([
                "success" => false,
                "code" => 400,
                "message" => "Account not found"
            ]);
        } else if (str_contains(strtolower($account->breachedby), 'profit')) {
            return response()->json([
                "success" => false,
                "code" => 400,
                "message" => "Account Reached Profit/Partial Profit"
            ]);
        }

        // $closeTrades = $account->closeRunningTrades();
        (new TradeService())->bulkTradeClose($account);
        $planRules = $account->planRules();
        $accountStartingBalance = $account->starting_balance;

        $server = $account->server;
        $url = $server->url;
        $sessionToken = $account->server->login;
        Redis::del('margin:' . $account->login); //!Delete Redis Key
        $redisDataFromApi = Http::get($url . "/user/margin/" . $account->login . "?token=" . $sessionToken);
        $redisData = json_decode($redisDataFromApi, 1);
        $currentBalance = $redisData['balance'];

        try {

            if ($accountStartingBalance <= $currentBalance) {
                $withdraw = Http::acceptJson()->post($url . "/user/withdraw?token=" . $sessionToken, [
                    'login' => $account->login,
                    'amount' => $currentBalance - $accountStartingBalance,
                    "is_credit" => false,
                    "comment" => "Withdraw topup",
                    "check_free_margin" => false,
                ]);
                if (json_decode($withdraw, 1)['code'] != 200) {
                    Session::flash('alert-danger', 'Error: Could not withdraw on account!');
                }
            } else {
                $deposit = Http::acceptJson()->post($url . "/user/deposit?token=" . $sessionToken, [
                    'login' => $account->login,
                    'amount' => $accountStartingBalance - $currentBalance,
                    "is_credit" => false,
                    "comment" => "Deposit-Topup",
                    "check_free_margin" => false,
                ]);

                if (json_decode($deposit, 1)['code'] != 200) {
                    return response()->json([
                        "success" => false,
                        "code" => 500,
                        "message" => "Could not deposit balance"
                    ]);
                }
            }

            if(isset($planRules['ANT'])){
                $ruleName = RuleName::whereCondition('ANT')->first();
                $this->accountService->removeRuleFromAccount($account,$ruleName);
            }

            $updateAccount = Http::acceptJson()->post($url . "/user/update?token=" . $sessionToken, [
                'login' => $account->login,
                'read_only' => 0,

            ]);

            if (json_decode($updateAccount, 1)['code'] != 200) {
                //! Error
                return response()->json([
                    "success" => false,
                    "code" => 500,
                    "message" => "Could not update account"
                ]);
            }

            $account->breached = false;
            $account->breachedby = null;
            $account->balance = $accountStartingBalance;
            $account->equity = $accountStartingBalance;

            $last_metric = $account->beforeLatestMetric->toJson();
            $breachMetric = $account->latestMetric->toJson();

            $subend = Helper::subend_days($account->duration);
            $subscription = Subscription::create([
                'account_id' => $account->id,
                'login' => $account->login,
                'plan_id' => $account->plan_id,
                'ending_at' => $subend['string'],
            ]);

            //!create topup log
            $saveTopupLog = TopupLog::create(
                [
                    'account_id' => $account->id,
                    'last_metric' => $last_metric,
                    'breach_metric' => $breachMetric,
                    'topup_amount' => $accountStartingBalance - $currentBalance,
                ]
            );


            //!Update Cycle Extension

            optional(ExtendCycleLog::whereAccountId($account->id)->latest())
                ->update(['eligibility' => 1]);

            //Clear Growth Fund
            if (isset($planRules['AGF'])) {
                $growthFund = GrowthFund::whereAccountId($account->id)->delete();
            }
            //!delete old metrics
            $account->beforeLatestMetric->delete();
            $account->latestMetric->delete();
            Redis::del('margin:' . $account->login);
            $account->unsetRelation('plan');
            $account->unsetRelation('accountRules');
            $account->unsetRelation('planRules');
            $account->push();
        } catch (\Exception $e) {
            Helper::discordAlert("**Auto Account Topup Error**:\nAccntID : " . $account->id . "\nLogin : " . $account->login . "\nError: " . $e->getMessage());
            Log::error($e);
            return response()->json([
                "success" => false,
                "code" => 400,
                "message" =>  $e
            ]);
        }

        Helper::discordAlert("**Auto Account Topup**:\nAccntID : " . $account->id . "\nLogin : " . $account->login . "\nAmount: " . $accountStartingBalance - $currentBalance);
        Cache::forget($account->id . ':firstTrade');

        return response()->json([
            "success" => true,
            "code" => 200,
            "message" => "Account topup/reset successful"
        ]);
    }
    // all servers fetch
    public function allServers(Request $request)
    {
        $servers = MtServer::all()->makeVisible('password');
        $servers = json_encode($servers);
        $key = env('FERNET_SECRET');
        $key = base64_encode($key);
        $fernet = new Fernet($key);
        $token = $fernet->encode($servers);
        return $token;
    }

    public function accountServer()
    {
        $accounts = Account::with('plan.server')->get();

        $filteredAccount = [];
        foreach ($accounts as $account) {
            $filteredAccount[] = [
                'id' => $account->id,
                'login' => $account->login,
                'server' => $account->server->friendly_name,

            ];
        }

        return  $filteredAccount;
    }

    public function accountSubscriptionResolve(Request $request)
    {
        $getAccounts = Account::with('plan', 'subscriptions', 'currentSubscription')->get();
        $foundAccount = [];
        $updateCount = 0;
        foreach ($getAccounts as $account) {
            if ($account->plan->type == 'Evaluation Real') {
                // dd($subscription);
                if (count($account->subscriptions) > 1) {
                    $endingSub = $account->currentSubscription;

                    $date = Carbon::createFromFormat('Y-m-d', $request->input('date'));
                    $endDate = Carbon::parse($endingSub->ending_at);
                    if ($endDate->isSameDay($date)) {
                        $login = $account->login;
                        $newSubend = Helper::subendDaysFrom(15, $endingSub->created_at)['string'];

                        $foundAccount[$login]['subend'] = $endingSub->ending_at;
                        $foundAccount[$login]['newSubend'] =  $newSubend;
                        $foundAccount[$login]['substart'] = $endingSub->created_at;
                        $foundAccount[$login]['account_id'] = $account->id;
                        $foundAccount[$login]['count'] = count($account->subscriptions);
                        $foundAccount[$login]['plan'] = $account->plan->description;

                        if ($request->has('update')) {
                            $updateCount = $updateCount + 1;
                            $account->currentSubscription->ending_at = $newSubend;
                            $account->currentSubscription->save();
                        }
                    }
                }
            }
        }
        return [$updateCount, $foundAccount];
    }
}
