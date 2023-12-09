<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Plan;
use App\Helper\Helper;
use App\Jobs\EmailJob;
use App\Models\Account;
use App\Models\BreachEvent;
use App\Jobs\BreachEventJob;
use App\Jobs\TradeCloseEvent;
use App\Constants\AppConstants;
use App\Services\AccountService;
use App\Constants\EmailConstants;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class RuleBreachService
{
    public $accountService;
    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }


    public function checkRuleBreach(Account $account, $margin, $planRules): bool
    {
        //TODO: Optimize plan rules


        $lastDayBalance = $account->lastDayMetric->lastBalance;
        $currentEquity = $margin['currentEquity'];

        $startingBalance = $account->starting_balance;

        $account->balance = $margin['currentBalance'];
        $account->equity = $margin['currentEquity'];

        if (isset($planRules['DLL'])) {

            $breachedDailyDrawdown = $this->checkDailyDrawdown($lastDayBalance, $currentEquity, $planRules['DLL']['value'], $startingBalance);

            if ($breachedDailyDrawdown) {
                //!take breach snapshot then breach then send email

                // $this->takeBreachEventSnapshot($account, $margin);
                BreachEventJob::dispatch($account, "Daily Loss Limit", $margin)->onQueue(AppConstants::QUEUE_BREACH_EVENT_JOB);


                return true;
            }
        }

        if (isset($planRules['MLL'])) {

            $breachedMonthlyLossLimit = $this->checkMonthlyLossLimit($currentEquity, $planRules['MLL']['value'], $startingBalance);

            if ($breachedMonthlyLossLimit) {
                //!take breach snapshot then breach then send email

                // $this->takeBreachEventSnapshot($account, $margin);
                BreachEventJob::dispatch($account, "Monthly Loss Limit", $margin)->onQueue(AppConstants::QUEUE_BREACH_EVENT_JOB);

                return true;
            }
        }

        return false;
    }



    public function breachEvent(Account $account, $breachedBy, $runningTrades)
    {
        $server       = $account->server;
        $url          = $server->url;
        $sessionToken = $server->login;

        $response = Http::post($url . "/user/update?token=" . $sessionToken, [
            'login' => $account->login,
            'read_only' => 1,
        ]);


        $response = json_decode($response->body());

        if($response->message == "User not found"){
            $account->breached   = true;
            $account->breachedby = "Account Deleted";
            $account->save();
        }
        else{
            $account->breached   = true;
            $account->breachedby = $breachedBy;

            TradeCloseEvent::dispatch($account, $runningTrades)->onQueue(AppConstants::QUEUE_TRADE_CLOSE_EVENT_JOB);
            $account->save();

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
        }
    }

    // public function takeBreachEventSnapshot($account, $margin)
    // {

    //     $snapMetric = $account->todayMetric;


    //     $openTrades = $account->thisCycleTrades()->where('close_time', 0)->get();


    //     foreach ($openTrades as &$openTrade) {
    //         $openTrade['profit'] = Redis::get('orderpl:' . $openTrade['ticket']);
    //         $openTrade['open_price'] = round($openTrade['open_price'], 4);
    //         $openTrade['close_price'] = round($openTrade['open_price'], 4);
    //         $openTrade['lots'] = $openTrade['volume'] / 100;
    //     }


    //     $breachSnap = BreachEvent::create(
    //         [
    //             'account_id' => $account->id,
    //             'login' => $account->login,
    //             'balance' =>  $margin['currentBalance'],
    //             'equity' => $margin['currentEquity'],
    //             'metrics' => json_encode($snapMetric),
    //             'trades' => json_encode($openTrades)

    //         ]
    //     );
    //     return $breachSnap;
    // }

    public function checkMetrics(Account $account, $margin,)
    {
        $metricsToInsertArray = [];

        $accountService = $this->accountService;
        $metricCount = count($account->latestTwoMetrics);
        $skipAccount = false;
        if ($metricCount == 0) {
            // $accountService->insertTwoDaysMetrics($account, $margin);

            $metricsToInsertArray[] =
                [
                    "account_id"          => $account->id,
                    "maxDailyLoss"        => 0,
                    "maxMonthlyLoss"      => 0,
                    "metricDate"          => Carbon::yesterday(),
                    "isActiveTradingDay"  => false,
                    "trades"              => 0,
                    "averageLosingTrade"  => 0,
                    "averageWinningTrade" => 0,
                    "lastBalance"         => $account->starting_balance,
                    "lastEquity"          => $account->starting_balance,
                    "lastRisk"            => 0,
                    "created_at"          => Carbon::yesterday(),
                    "updated_at"          => Carbon::yesterday()
                ];
            $metricsToInsertArray[] =    [
                "account_id"          => $account->id,
                "maxDailyLoss"        => 0,
                "maxMonthlyLoss"      => 0,
                "metricDate"          => Carbon::today(),
                "isActiveTradingDay"  => false,
                "trades"              => 0,
                "averageLosingTrade"  => 0,
                "averageWinningTrade" => 0,
                'lastBalance'         => $margin['currentBalance'],
                'lastEquity'          => $margin['currentEquity'],
                "lastRisk"            => 0,
                "created_at"          => Carbon::now(),
                "updated_at"          => Carbon::now()

            ];




            // $accountService->createYesterdayMetric($account);
            // $accountService->createTodayMetric($account, $margin);
            $skipAccount = true;
            return [
                'skipAccount' => $skipAccount,
                'metricsToInsertArray' => $metricsToInsertArray
            ];
        } else if ($metricCount == 1) {
            // $accountService->insertTodayMetric($account, $margin);
            $metricsToInsertArray[] =    [
                "account_id"          => $account->id,
                "maxDailyLoss"        => 0,
                "maxMonthlyLoss"      => 0,
                "metricDate"          => Carbon::today(),
                "isActiveTradingDay"  => false,
                "trades"              => 0,
                "averageLosingTrade"  => 0,
                "averageWinningTrade" => 0,
                'lastBalance'         => $margin['currentBalance'],
                'lastEquity'          => $margin['currentEquity'],
                "lastRisk"            => 0,
                "created_at"          => Carbon::now(),
                "updated_at"          => Carbon::now()

            ];


            // $accountService->createTodayMetric($account, $margin);
            $skipAccount = true;
            return [
                'skipAccount' => $skipAccount,
                'metricsToInsertArray' => $metricsToInsertArray
            ];
        }

        return [
            'lastDayMetric' => $account->latestTwoMetrics[0],
            'todayMetric' => $account->latestTwoMetrics[1],
            'skipAccount' => $skipAccount,
        ];
    }
    protected function checkDailyDrawdown($lastDayBalance, $currentEquity, $dllRule, $startingBalance): bool
    {
        if ((($lastDayBalance - $currentEquity)  > ($dllRule / 100 *  $startingBalance)) && ($lastDayBalance > $currentEquity)) {
            return true;
        } else {
            return false;
        }
    }
    protected function checkMonthlyLossLimit($currentEquity, $mllRule, $startingBalance): bool
    {

        if (($currentEquity  < ((1 - ($mllRule) / 100) * $startingBalance))) {

            return true;
        } else {
            return false;
        }
    }
    public function notifyBackend($account, $breachedby)
    {
        $notifyBreach = Http::withHeaders([
            'Accept' => 'application/json',
            'X-Verification-Key' => env('WEBHOOK_TOKEN'),
        ])->post(env('FRONTEND_URL') . "/api/v1/webhook/rule-breaches", [

            "accountHistoryId" => $account->todayMetric->id,
            "accountId"        => $account->id,
            "type"             => $breachedby,
            "message"          => "The account $account->login is paused due to : $breachedby ",
            "pushed"           => 0,
            "Breached"         => true,
            "utcTime"          => gmdate("Y-m-d H:i:s"),
            "inserted"         => gmdate("Y-m-d H:i:s"),
            "updated"          => gmdate("Y-m-d H:i:s"),

        ]);
    }


    public function takeBreachEventSnapshot($account, $margin, &$runningTrades)
    {
        if (!sizeof($runningTrades)) {
            $runningTrades = (new TradeService())->getRunningTrades($account);
        }
        $snapMetric = $account->todayMetric;
        BreachEvent::create(
            [
                'account_id' => $account->id,
                'login' => $account->login,
                'balance' =>  $margin['currentBalance'],
                'equity' => $margin['currentEquity'],
                'metrics' => json_encode($snapMetric),
                'trades' => json_encode(array_values($runningTrades))
            ]
        );
    }
}
