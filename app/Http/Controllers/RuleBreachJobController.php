<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use Carbon\Carbon;
use App\Models\Plan;
use App\Helper\Helper;
use App\Models\Account;
use App\Models\MtServer;
use App\Models\PlanRule;
use Batch;
use App\Jobs\SendEmailJob;
use App\Models\AccountRule;
use App\Models\BreachEvent;
use Illuminate\Http\Request;
use App\Models\AccountMetric;
use App\Jobs\RuleBreachChecker;
use App\Services\AccountService;
use App\Services\PlanRulesService;
use Illuminate\Support\Facades\DB;
use App\Services\RuleBreachService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use App\Services\SendGrid\SendMailService;


class RuleBreachJobController extends Controller
{


    public function test()
    {

        $accountList = DB::select(DB::raw("SELECT account_id ,SUM(volume)/100 AS lots_traded FROM trades where trades.account_id in (SELECT account_id FROM account_metrics WHERE metricDate= '2022-08-17 00:00:00' AND isActiveTradingDay=1) and DATE(created_at) = DATE('2022-08-17')  GROUP BY account_id HAVING lots_traded<=1"));




        // return $accountList;


        $ar = 0;
        foreach ($accountList as $account) {

            $account = Account::with('lastDayMetric')->find($account->account_id);

            $lastDayMetric = $account->lastDayMetric;
            $lastDayMetric->lastBalance = $account->balance;
            $lastDayMetric->lastEquity = $account->equity;

            $lastDayMetric->push();

            $ar += 1;
        }

        return $ar;


        // $today = Carbon::today()->format('Y-m-d');



        // $accountMetrics = AccountMetric::with('account', 'account.lastDayMetric')->where('metricDate', $today)->where('isActiveTradingDay', 0)->get();
        // // return count($accountMetrics);
        // $ar = 0;
        // foreach ($accountMetrics as $accountMetric) {
        //     $account = $accountMetric->account;
        //     $lastDayMetric = $account->lastDayMetric;
        //     $lastDayMetric->lastBalance = $account->balance;
        //     $lastDayMetric->lastEquity = $account->equity;

        //     $lastDayMetric->push();
        //     $ar += 1;
        // }
        // return $ar;
    }

    public function rulesCheckerV2()
    {
        $initialStartTime = Carbon::now();

        $attempts = 0;


        $accountsToUpdate = [];
        $accountMetricsToUpdate = [];


        while ($attempts++ < 1) {
            //ob_start();

            $startTime = Carbon::now();

            $notBreachedAccounts = Account::with(['lastDayMetric', 'plan', 'plan.server', 'todayMetric'])->where('breached', '0')->get();

            // return $notBreachedAccounts;
            // $notBreachedAccounts = Account::find(2);
            if ($notBreachedAccounts == null) return response()->json(['no account found to update'], 200);
            // return $notBreachedAccounts;

            foreach ($notBreachedAccounts as $account) {
                $server = $account->server;
                $url = $server->url;
                $telescope[] = "URL :" . $url;

                $sessionToken = $server->login;

                // $account =  Account::find(2);
                $telescope[] = "====================ID: " . $account->id . "  Login :" . $account['login'];
                $telescope[] = "";
                $planRules = [];

                $planRules = $account->planRules();




                try {
                    //! account metric creation for today when last day metric is availabe
                    if (($account->lastDayMetric) != null) {

                        // return "lastday metric paise ".$account->lastDayMetric;
                        $telescope[] = "lastDayMetric is not null";

                        $lastDayBalance = $account->lastDayMetric->lastBalance;
                        $accountBalance = $account->balance;
                        //$lastDayEquity = $account->lastDayMetric->lastEquity;
                        $todayMetric = $account->todayMetric;
                        // $todayMetric =
                        //     AccountMetric::whereDate('metricDate', '=', Carbon::today()->toDateString())
                        //     ->where('account_id', '=', $account->id)->first();
                        if ($todayMetric == null) {
                            //

                            $telescope[] = "Creating todays metric";

                            $todayMetric = AccountMetric::create(

                                [
                                    "account_id" => $account->id,
                                    "maxDailyLoss" => 0,
                                    "maxMonthlyLoss" => 0,
                                    "metricDate" => Carbon::today(),
                                    "isActiveTradingDay" =>  false,
                                    "trades" => 0,
                                    "averageLosingTrade" => 0,
                                    "averageWinningTrade" => 0,
                                    "lastBalance" => $lastDayBalance,
                                    "lastEquity" =>  $accountBalance,
                                    "lastRisk" =>  0,

                                ]
                            );
                        }
                    } else {
                        // return "lastday metric paynai";
                        $telescope[] = "Creating todays metric and lastDayMetric";
                        //! if last day metric is not available then create both
                        $oldmetric = AccountMetric::insert([
                            [
                                "account_id" => $account->id,
                                "maxDailyLoss" => 0,
                                "maxMonthlyLoss" => 0,
                                "metricDate" => Carbon::yesterday(),
                                "isActiveTradingDay" =>  false,
                                "trades" => 0,
                                "averageLosingTrade" => 0,
                                "averageWinningTrade" => 0,
                                "lastBalance" => $account->starting_balance,
                                "lastEquity" => $account->starting_balance,
                                "lastRisk" =>  0,
                                "created_at" => Carbon::yesterday(),
                                "updated_at" => Carbon::yesterday()
                            ],

                            [
                                "account_id" => $account->id,
                                "maxDailyLoss" => 0,
                                "maxMonthlyLoss" => 0,
                                "metricDate" => Carbon::today(),
                                "isActiveTradingDay" =>  false,
                                "trades" => 0,
                                "averageLosingTrade" => 0,
                                "averageWinningTrade" => 0,
                                "lastBalance" => $account->starting_balance,
                                "lastEquity" => $account->starting_balance,
                                "lastRisk" =>  0,
                                "created_at" => Carbon::now(),
                                "updated_at" => Carbon::now()

                            ]

                        ]);


                        // dd($account->lastDayMetric);
                        $lastDayBalance =  $account->starting_balance;

                        $accountBalance = $account->balance;
                        $lastDayEquity = $lastDayBalance;

                        //! if last day metric is not available then create both END
                    }

                    $accountStartingBalance = $account->starting_balance;
                    //$url = $account->server->url; || Duplicate URL Fetching

                    $telescope[] = "URL :" . $url;

                    try {



                        $redisData = json_decode(Redis::get('margin:' . $account->login), 1);
                        if ($redisData != null) {

                            $telescope[] = "Redis available";

                            $currentBalance = $redisData['balance'];
                            $currentEquity = $redisData['equity'];
                        } else {
                            //return response()->json(['Error' => "Couldn't connect to redis"]);

                            $telescope[] = "Redis not available so fetching from API";

                            //TODO: if redis not found the create an alert

                            $redisDataFromApi = Http::get($url . "/user/margin/" . $account->login . "?token=" . $sessionToken);
                            $redisData = json_decode($redisDataFromApi, 1);
                            $currentBalance = $redisData['balance'];
                            $currentEquity = $redisData['equity'];
                            Redis::set('margin:' . $account->login, json_encode(['balance' => $currentBalance, 'equity' => $currentEquity]));
                        }
                    } catch (\Exception $e) {

                        $telescope[] = "Redis not available so fetching from API";

                        //TODO: if redis not found the create an alert

                        $redisDataFromApi = Http::get($url . "/user/margin/" . $account->login . "?token=" . $sessionToken);
                        $redisData = json_decode($redisDataFromApi, 1);
                        $currentBalance = $redisData['balance'];
                        $currentEquity = $redisData['equity'];
                        Redis::set('margin:' . $account->login, json_encode(['balance' => $currentBalance, 'equity' => $currentEquity]));
                    }



                    //! update current balance to lastBalance of today's metric
                    $account->latestMetric->lastBalance = $currentBalance;
                    //TODO: should I update lastEquity?
                    $account->latestMetric->lastEquity = $currentEquity;
                    $account->balance = $currentBalance;
                    $account->equity = $currentEquity;

                    // $update = $account->update([
                    //     'balance' => $currentBalance,
                    //     'equity' => $currentEquity,
                    // ]);




                    $telescope[] = "Equity : $currentEquity , Balance: $currentBalance";
                    $telescope[] = "Current Equity:  $currentEquity";


                    $maxDailyLoss = $currentEquity - $lastDayBalance;  //! Reconfirm This
                    $maxMonthlyLoss = (($currentEquity - $accountStartingBalance) < 0) ? $currentEquity - $accountStartingBalance : 0; //! reconfirm This

                    $telescope[] = "Max Monthly Loss: $maxMonthlyLoss";
                    // $telescope[] = "OLD Equity: $oldEquity";

                    $telescope[] = "Last Day Balance : $lastDayBalance";
                    $telescope[] = "Max Daily Loss : $maxDailyLoss";

                    if (($account->latestMetric->maxDailyLoss) > ($maxDailyLoss)) {

                        $telescope[] = "Max Daily Loss updated. New value: $maxDailyLoss";
                        $account->latestMetric->maxDailyLoss = $maxDailyLoss;
                    }
                    // !max monthly loss update
                    if ($maxMonthlyLoss < $account->latestMetric->maxMonthlyLoss) {
                        $telescope[] = "Max Loss updated. New value: $maxMonthlyLoss";
                        $account->latestMetric->maxMonthlyLoss = $maxMonthlyLoss;
                    }

                    if (isset($planRules['DLL'])) {

                        $telescope[] = "DLL rule : " . $planRules['DLL']['value'];
                        if ((($lastDayBalance - $currentEquity)  > ($planRules['DLL']['value'] / 100 * $accountStartingBalance)) && ($lastDayBalance > $currentEquity)) {

                            $telescope[] = "===== Breached by DLL rule ===== ";
                            $account->unsetRelation('plan');
                            $account->unsetRelation('accountRules');
                            $account->push();
                            //!sendMail
                            $details = [
                                'template_id' => SendMailService::A_RULE_HASBEEN_BREACHED_ON_ACCOUNT,
                                'name' => Helper::getOnlyCustomerName($account->customer->name),
                                'email' => $account->customer->email,
                                'login_id' => $account->login
                            ];

                            SendEmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_ON_BREACH_NOTIFY_USER_JOB);
                            // dispatch(new SendEmailJob($details));

                            breachEventSnapshot($account);
                            //!breach DLL rule
                            $controller = new \App\Http\Controllers\AccountController();
                            $controller->breachEvent($account, $planRules['DLL']['rule']);
                            continue;
                        }
                    }

                    // return (1 - ($planRules['MLL']['value']) / 100) * $accountStartingBalance;
                    if (isset($planRules['MLL'])) {
                        $telescope[] = "MLL rule : " . $planRules['MLL']['value'];
                        if (($currentEquity  < ((1 - ($planRules['MLL']['value']) / 100) * $accountStartingBalance))) {

                            $telescope[] = "===== Breached by MLL rule ===== ";
                            $account->unsetRelation('plan');
                            $account->unsetRelation('accountRules');
                            $account->push();

                            //!sendMail
                            $details = [
                                'template_id' => SendMailService::A_RULE_HASBEEN_BREACHED_ON_ACCOUNT,
                                'name' => Helper::getOnlyCustomerName($account->customer->name),
                                'email' => $account->customer->email,
                                'login_id' => $account->login
                            ];

                            SendEmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_ON_BREACH_NOTIFY_USER_JOB);
                            // dispatch(new SendEmailJob($details));

                            breachEventSnapshot($account);
                            //!breach MLL rule
                            $controller = new \App\Http\Controllers\AccountController();
                            $controller->breachEvent($account, $planRules['MLL']['rule']);
                            continue;
                        }
                    }




                    $latestMetric = $account->latestMetric;
                    // $account->push();
                    array_push($accountMetricsToUpdate, [
                        "id" => $latestMetric->id,
                        "account_id" => $latestMetric->account_id,
                        "maxDailyLoss" => $latestMetric->maxDailyLoss,
                        "metricDate" => $latestMetric->metricDate,
                        "isActiveTradingDay" => $latestMetric->isActiveTradingDay,
                        "trades" => $latestMetric->trades,
                        "averageLosingTrade" => $latestMetric->averageLosingTrade,
                        "lastBalance" => $latestMetric->lastBalance,
                        "lastEquity" => $latestMetric->lastEquity,
                        "lastRisk" => $latestMetric->lastRisk,
                        "maxMonthlyLoss" => $latestMetric->maxMonthlyLoss
                    ]);
                    array_push($accountsToUpdate, [
                        "id" => $account->id,
                        "customer_id" => $account->customer_id,
                        "login" => $account->login,
                        "password" => $account->password,
                        "type" => $account->type,
                        "plan_id" => $account->plan_id,
                        "name" => $account->name,
                        "trading_server_type" => $account->trading_server_type,
                        "comment" => $account->comment,
                        "balance" => $account->balance,
                        "equity" => $account->equity,
                        "credit" => $account->credit,
                        "breached" => $account->breached,
                        "breachedBy" => $account->breachedBy,
                    ]);
                } catch (\Exception $e) {


                    throw $e;
                }
            }
            $endTime = Carbon::now();



            $telescope[] = "Time Taken: " . $endTime->diffInMilliseconds($startTime) . " (ms)";
        }

        DB::table('accounts')->upsert($accountsToUpdate, ['id'], ['balance', 'equity']);

        DB::table('account_metrics')->upsert($accountMetricsToUpdate, ['id'], ['lastBalance', 'lastEquity', 'maxDailyLoss', 'maxMonthlyLoss']);

        // Account::upsert($accountsToUpdate, ['id'], ['balance', 'equity']);
        // AccountMetric::upsert($accountMetricsToUpdate, ['id'], ['lastBalance', 'lastEquity', 'maxDailyLoss', 'maxMonthlyLoss']);

        $telescope[] = "Total Exec Time : " . $endTime->diffInMilliseconds($initialStartTime) . " (ms)";

        return $telescope;
    }



    public function rulesCheckerV3(RuleBreachService $ruleBreachService)
    {

        $accountsToUpdate = [];
        $accountMetricsToUpdate = [];
        $accountService = new AccountService();
        $notBreachedAccounts = Account::with(['latestTwoMetrics', 'plan', 'server'])->where('breached', '0')->get(['id', 'server_id', 'login', 'plan_id',  'balance', 'equity', 'breached', 'breachedBy','starting_balance']);


        // return $notBreachedAccounts;
        // $notBreachedAccounts = Account::with(['latestTwoMetrics', 'plan', 'plan.server'])->where('breached', '0')->where('login', 1820772750)->get();
        $planRulesService = new PlanRulesService(true);

        if ($notBreachedAccounts == null) return response()->json(['no account found to update'], 200);

        $accountMetricsToInsert = [];
        foreach ($notBreachedAccounts as $account) {
            $rules = $planRulesService->getRules($account);


            $server = $account->server;
            $url = $server->url;
            $telescope[] = "URL :" . $url;


            //! getting margin and metrics. Create metrics if
            $margin = $accountService->margin($account);
            // $margin = [];
            // $margin['currentBalance'] = 100;
            // $margin['currentEquity'] = 100;
            $currentBalance = $margin['currentBalance'];
            $currentEquity = $margin['currentEquity'];
            $metrics = $ruleBreachService->checkMetrics($account, $margin);



            if ($metrics['skipAccount']) {
                array_push($accountMetricsToInsert, ...$metrics['metricsToInsertArray']);
                // $accountMetricsToUpdate[] = $metrics['metricsToInsertArray'];
                continue;
            }

            // return $account->latestTwoMetrics;


            //! assigning metrics from the latest two metrics of the account metrics table
            $lastDayMetric = $metrics['lastDayMetric'];
            $todayMetric = $metrics['todayMetric'];

            //! account metrics assigned
            $account->lastDayMetric = $lastDayMetric;
            $account->todayMetric = $todayMetric;
            $account->balance = $margin['currentBalance'];
            $account->equity = $margin['currentEquity'];




            $lastDayBalance = $account->lastDayMetric->lastBalance;
            $startingBalance = $account->starting_balance;



            $maxDailyLoss = $currentEquity - $lastDayBalance;
            $maxMonthlyLoss = (($currentEquity -  $startingBalance) < 0) ? $currentEquity -  $startingBalance : 0; //! reconfirm This



            if (($account->todayMetric->maxDailyLoss) > ($maxDailyLoss)) {
                $account->todayMetric->maxDailyLoss = $maxDailyLoss;
            }
            // !max monthly loss update
            if ($maxMonthlyLoss < $account->todayMetric->maxMonthlyLoss) {
                $account->todayMetric->maxMonthlyLoss = $maxMonthlyLoss;
            }

            // $rules = [
            //     'DLL' => [
            //         'rule' => 'Daily Loss Limit',
            //         'condition' => 'DLL',
            //         'value' => '5',
            //         'is_percent' => 1,
            //     ],
            //     'MLL' => [
            //         'rule' => 'Monthly Loss Limit',
            //         'condition' => 'MLL',
            //         'value' => '10',
            //         'is_percent' => 1,
            //     ],
            // ];

            $ruleBreachService->checkRuleBreach($account, $margin, $rules);






            $todayMetric->lastBalance = $currentBalance;
            $todayMetric->lastEquity = $currentEquity;


            $latestMetric = $todayMetric;



            array_push($accountsToUpdate, [
                "id" => $account->id,
                "balance" => $account->balance,
                "equity" => $account->equity,
            ]);
            array_push($accountMetricsToUpdate, [
                "id" => $latestMetric->id,
                "maxDailyLoss" => $latestMetric->maxDailyLoss,
                "lastBalance" => $latestMetric->lastBalance,
                "lastEquity" => $latestMetric->lastEquity,
                "maxMonthlyLoss" => $latestMetric->maxMonthlyLoss
            ]);
        }
        if (sizeof($accountMetricsToInsert)) {
            $accountMetricChunks = array_chunk($accountMetricsToInsert, 3000);
            foreach ($accountMetricChunks as $accountMetricChunk) {
                AccountMetric::insert($accountMetricChunk);
            }
            // $updateAccountMetrics = AccountMetric::insert($accountMetricsToInsert);
        }


        $accountInstance = new Account;
        $accountInstance->timestamps = false;
        $accountIndex = 'id';
        if (sizeof($accountsToUpdate)) {
            Batch::update($accountInstance, $accountsToUpdate, $accountIndex);
        }

        $accountMetricsInstance = new AccountMetric;
        $accountMetricsInstance->timestamps = false;
        $accountMetricsIndex = 'id';
        if (sizeof($accountMetricsToUpdate)) {
            Batch::update($accountMetricsInstance, $accountMetricsToUpdate, $accountMetricsIndex);
        }



        return count($notBreachedAccounts);
    }

    public function ruleBreachDispatcher()
    {
        // return 1;
        $notBreachedAccounts = Account::with(['lastDayMetric', 'latestMetric', 'plan', 'todayMetric'])->where('breached', '0')->get();

        $notBreachedAccounts = $notBreachedAccounts->chunk(400);
        $notBreachedAccounts = $notBreachedAccounts->map(function ($chunk) {
            return $chunk = $chunk->values();
        });

        foreach ($notBreachedAccounts as $accountGroup) {
            $now = now();
            RuleBreachChecker::dispatch($accountGroup)->onQueue('rule-breach');
        }
        return ["Done", $now, now()];
    }
}

function breachEventSnapshot(Account $account)
{


    $snapMetric = $account->latestMetric()->select('maxDailyLoss', 'maxMonthlyLoss', 'lastBalance', 'lastEquity')->first();
    // $metrics=AccountMetric::select('maxDailyLoss','maxMonthlyLoss','lastBalance','lastEquity')->whereId($account->id)->


    $openTrades = $account->thisCycleTrades()->where('close_time', 0)->get();


    foreach ($openTrades as &$openTrade) {
        $openTrade['profit'] = Redis::get('orderpl:' . $openTrade['ticket']);
        $openTrade['open_price'] = round($openTrade['open_price'], 4);
        $openTrade['close_price'] = round($openTrade['open_price'], 4);
        $openTrade['lots'] = $openTrade['volume'] / 100;
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

        if (isset($sessionToken['error'])) {
            return response()->json(['message' => 'MT4 Server timeout'], 500);
        }
        $redisDataFromApi = Http::get($url . "/user/margin/" . $account->login . "?token=" . $sessionToken);
        $redisData = json_decode($redisDataFromApi, 1);
        $currentBalance = $redisData['balance'];
        $currentEquity = $redisData['equity'];
        Redis::set('margin:' . $account->login, json_encode(['balance' => $currentBalance, 'equity' => $currentEquity]));
    }

    $breachSnap = BreachEvent::create(
        [
            'account_id' => $account->id,
            'login' => $account->login,
            'balance' =>  $currentBalance,
            'equity' => $currentEquity,
            'metrics' => json_encode($snapMetric),
            'trades' => json_encode($openTrades)

        ]
        // $details = [
        //     'template_id' => SendMailService::A_RULE_HASBEEN_BREACHED_ON_ACCOUNT,

        // ]
        // dispatch(new SendEmailJob($details));
    );




    return $breachSnap;
}
