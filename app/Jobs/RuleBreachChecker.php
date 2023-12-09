<?php

namespace App\Jobs;

use App\Constants\AppConstants;
use App\Helper\Helper;
use Carbon\Carbon;
use App\Models\Account;
use App\Jobs\SendEmailJob;
use App\Models\BreachEvent;
use App\Models\AccountMetric;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\SendGrid\SendMailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Http\Controllers\RuleBreachJobController;

class RuleBreachChecker implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $accountGroups;
    public function __construct($accountGroups)
    {
        $this->accountGroups = $accountGroups;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // return 1 + 1;
        $accountsToUpdate = [];
        $accountMetricsToUpdate = [];
        $accountGroups = $this->accountGroups;
        foreach ($accountGroups as $account) {
            $server = $account->server;
            $url = $server->url;

            $sessionToken = $server->login;
            $planRules = $account->planRules();



            try {
                //! account metric creation for today when last day metric is availabe
                if (($account->lastDayMetric) != null) {

                    // return "lastday metric paise ".$account->lastDayMetric;


                    $lastDayBalance = $account->lastDayMetric->lastBalance;
                    $accountBalance = $account->balance;
                    //$lastDayEquity = $account->lastDayMetric->lastEquity;
                    $todayMetric = $account->todayMetric;
                    // $todayMetric =
                    //     AccountMetric::whereDate('metricDate', '=', Carbon::today()->toDateString())
                    //     ->where('account_id', '=', $account->id)->first();
                    if ($todayMetric == null) {
                        //



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



                try {
                    $redisData = json_decode(Redis::get('margin:' . $account->login), 1);
                    if ($redisData != null) {



                        $currentBalance = $redisData['balance'];
                        $currentEquity = $redisData['equity'];
                    } else {
                        //return response()->json(['Error' => "Couldn't connect to redis"]);



                        //TODO: if redis not found the create an alert

                        $redisDataFromApi = Http::get($url . "/user/margin/" . $account->login . "?token=" . $sessionToken);
                        $redisData = json_decode($redisDataFromApi, 1);
                        $currentBalance = $redisData['balance'];
                        $currentEquity = $redisData['equity'];
                        Redis::set('margin:' . $account->login, json_encode(['balance' => $currentBalance, 'equity' => $currentEquity]));
                    }
                } catch (\Exception $e) {


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





                $maxDailyLoss = $currentEquity - $lastDayBalance;  //! Reconfirm This
                $maxMonthlyLoss = (($currentEquity - $accountStartingBalance) < 0) ? $currentEquity - $accountStartingBalance : 0; //! reconfirm This



                if (($account->latestMetric->maxDailyLoss) > ($maxDailyLoss)) {


                    $account->latestMetric->maxDailyLoss = $maxDailyLoss;
                }
                // !max monthly loss update
                if ($maxMonthlyLoss < $account->latestMetric->maxMonthlyLoss) {

                    $account->latestMetric->maxMonthlyLoss = $maxMonthlyLoss;
                }

                if (isset($planRules['DLL'])) {


                    if ((($lastDayBalance - $currentEquity)  > ($planRules['DLL']['value'] / 100 * $accountStartingBalance)) && ($lastDayBalance > $currentEquity)) {


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

                        // SendEmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_ON_BREACH_NOTIFY_USER_JOB);
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

                    if (($currentEquity  < ((1 - ($planRules['MLL']['value']) / 100) * $accountStartingBalance))) {


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

        Account::upsert($accountsToUpdate, ['id'], ['balance', 'equity']);
        AccountMetric::upsert($accountMetricsToUpdate, ['id'], ['lastBalance', 'lastEquity', 'maxDailyLoss', 'maxMonthlyLoss']);
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
