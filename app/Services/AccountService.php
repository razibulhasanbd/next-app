<?php

namespace App\Services;

use Throwable;
use Carbon\Carbon;
use App\Models\Plan;
use App\Helper\Helper;
use App\Models\Account;

use App\Jobs\TradeClose;

use App\Models\RuleName;

use Illuminate\Bus\Batch;
use App\Models\GrowthFund;
use App\Models\Subscription;
use App\Models\AccountMetric;
use App\Constants\AppConstants;
use App\Models\AccountRule;
use Exception;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class AccountService
{

    /**
     * Deposit balance to an account and delete the redis margin
     *
     * @param Account $account
     * @param float $amount
     * @return void
     */
    public function deposit(Account $account, $amount)
    {
        $server = $account->server;
        $url = $server->url;
        $sessionToken = $server->login;
        $login = $account->login;

        $deposit = Http::acceptJson()->post($url . "/user/deposit?token=" . $sessionToken, [

            'login' => $login,
            'amount' => $amount,
            "is_credit" => false,
            "comment" => "Direct-Deposit",
            "check_free_margin" => false,
        ]);

        $this->delRedisMargin($login);
        $getMarginDetails = $this->margin($account);
        if (json_decode($deposit, 1)['code'] = 200) {
            return response()->json(['message' => 'Deposit Done', 'margin' => $getMarginDetails], 200);
        }
    }

    /**
     * Withdraw balance from an account and delete the redis margin
     *
     * @param Account $account
     * @param float $amount
     * @return void
     */
    public function withdraw(Account $account, $amount)
    {
        $server = $account->server;
        $url = $server->url;
        $sessionToken = $server->login;
        $login = $account->login;

        $withdraw = Http::acceptJson()->post($url . "/user/withdraw?token=" . $sessionToken, [
            'login' =>  $login,
            'amount' => $amount,
            "is_credit" => false,
            "comment" => "Direct-Withdraw",
            "check_free_margin" => false,
        ]);
        $this->delRedisMargin($login);
        $getMarginDetails = $this->margin($account);
        if (json_decode($withdraw, 1)['code'] = 200) {
            return response()->json(['message' => 'withdraw Done', 'margin' => $getMarginDetails], 200);
        }
    }

    /**
     * Get list of groups in a server
     *
     * @param mixed $details
     * @return Response
     */
    public function getAllGroups($details)
    {
        $getAllGroups = Http::acceptJson()->get($details['url'] . "/groups?token=" . $details['sessionToken']);
        return $getAllGroups;
    }

    /**
     * Change the group of an account
     *
     * @param mixed $details
     * @return ResponseFactory|Response
     */
    public function updateGroup($details)
    {
        $updateAccountGroup = Http::acceptJson()->post($details['url'] . "/user/update?token=" . $details['sessionToken'], [
            'login' => $details['login'],
            'group' => $details['group'],

        ]);
        if (json_decode($updateAccountGroup, 1)['code'] = 200) {
            return response()->json(['message' => 'Account Group Change'], 200);
        }
    }

    /**
     * Get the current group of an Account
     *
     * @param mixed $details
     * @return string
     */
    public function userReport($details): string
    {
        $userReport = Http::acceptJson()->get($details['url'] . "/user/" . $details['login'] . "?token=" . $details['sessionToken']);
        return $userReport['group'];
    }

    /**
     * Delete the margin key for an account from redis
     *
     * @param int|string $login
     * @return void
     */
    public function delRedisMargin($login): void
    {
        Redis::del('margin:' . $login); //!Delete Redis Key
    }

    /**
     * Get the total number of active trading days for an account in it's latest subscription cycle
     *
     * @param Account $account
     * @return int
     */
    public function tradingDays(Account $account): int
    {
        $currentSubscription = $account->currentSubscription;

        $subscriptionStartDate = $currentSubscription->created_at->format('Y-m-d');
        $activeMetrics = AccountMetric::where('account_id', $account->id)->where('created_at', '>=', $subscriptionStartDate)->where('isActiveTradingDay', 1)->get();

        return $activeMetrics->count();

        // $thisMonthMetric = $account->thisCycleMetrics;
        // return $thisMonthMetric->where('isActiveTradingDay', 1)->count();
    }

    /**
     * Get account margin from redis. If it fails fetch from http request
     *
     * @param Account $account
     * @return array
     */
    public function margin(Account $account): array
    {
        try {
            $redisData = json_decode(Redis::get('margin:' . $account->login), 1);
            if ($redisData != null) {
                $currentBalance = $redisData['balance'];
                $currentEquity = $redisData['equity'];
                return ['currentBalance' => $currentBalance, 'currentEquity' => $currentEquity];
            } else {
                //return response()->json(['Error' => "Couldn't connect to redis"]);
                //TODO: if redis not found the create an alert
                $server = $account->server;

                $redisDataFromApi = Http::get($server->url . "/user/margin/" .  $account->login . "?token=" . $server->login);
                $redisData = json_decode($redisDataFromApi, 1);
                $currentBalance = $redisData['balance'];
                $currentEquity = $redisData['equity'];
                Redis::set('margin:' . $account->login, json_encode(['balance' => $currentBalance, 'equity' => $currentEquity]));
                return ['currentBalance' => $currentBalance, 'currentEquity' => $currentEquity];
            }
        } catch (\Exception $e) {
            $server = $account->server;
            //TODO: if redis not found the create an alert
            $redisDataFromApi = Http::get($server->url . "/user/margin/" .  $account->login . "?token=" . $server->login);
            $redisData = json_decode($redisDataFromApi, 1);
            $currentBalance = $redisData['balance'];
            $currentEquity = $redisData['equity'];
            Redis::set('margin:' . $account->login, json_encode(['balance' => $currentBalance, 'equity' => $currentEquity]));
            return ['currentBalance' => $currentBalance, 'currentEquity' => $currentEquity];
        }
    }

    /**
     * Create a new account metric for an account for present day. 
     *
     * @param Account $account
     * @param array $margin
     * @return 
     */
    public function insertTodayMetric(Account $account, $margin)
    {

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
                'lastBalance' => $margin['currentBalance'],
                'lastEquity' => $margin['currentEquity'],
                "lastRisk" =>  0,

            ]
        );

        return $todayMetric;
    }

    /**
     * Create two new account metrics for an account for present day and previous day 
     *
     * @param Account $account
     * @param array $margin
     * @return 
     */
    public function insertTwoDaysMetrics(Account $account, $margin)
    {
        $twoMetrics = AccountMetric::insert([
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
                'lastBalance' => $margin['currentBalance'],
                'lastEquity' => $margin['currentEquity'],
                "lastRisk" =>  0,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()

            ]

        ]);
    }

    /**
     * Create a new account metric or update an existing one for an account for present day.
     *
     * @param Account $account
     * @param array $margin
     * @return void
     */
    public function createTodayMetric(Account $account, $margin)
    {

        $todayMetric = AccountMetric::updateOrCreate([
            'account_id' => $account->id,
            'metricDate' => Carbon::today(),
        ], [
            'maxDailyLoss' => 0,
            'maxMonthlyLoss' => 0,
            'metricDate' => Carbon::today(),
            'isActiveTradingDay' =>  false,
            'trades' => 0,
            'averageLosingTrade' => 0,
            'averageWinningTrade' => 0,
            'lastBalance' => $margin['currentBalance'],
            'lastEquity' => $margin['currentEquity'],
            'lastRisk' =>  0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return $todayMetric;
    }

    /**
     * Create a new account metric or update an existing one for an account for yesterday.
     *
     * @param Account $account
     * @return void
     */
    public function createYesterdayMetric(Account $account)
    {

        $yesterdayMetric = AccountMetric::updateOrCreate([
            'account_id' => $account->id,
            'metricDate' => Carbon::yesterday(),
        ], [
            'maxDailyLoss' => 0,
            'maxMonthlyLoss' => 0,
            'metricDate' => Carbon::yesterday(),
            'isActiveTradingDay' =>  false,
            'trades' => 0,
            'averageLosingTrade' => 0,
            'averageWinningTrade' => 0,
            'lastBalance' => $account->starting_balance,
            'lastEquity' => $account->starting_balance,
            'lastRisk' =>  0,
            'created_at' => Carbon::yesterday(),
            'updated_at' => Carbon::yesterday()
        ]);

        return $yesterdayMetric;
    }

    /**
     * Enable an account for trading
     *
     * @param mixed $details
     * @return void
     */
    public function accountOn($details)
    {
        Http::acceptJson()->post($details['url'] . "/user/update?token=" . $details['sessionToken'], [
            'login' => $details['login'],
            'read_only' => 0,

        ]);
    }

    /**
     * Add growth fund to an account. Creates a new row inside growth_funds table.
     *
     * @param mixed $details
     * @return 
     */
    public function addGrowthFund($details)
    {
        $growthFund = GrowthFund::create($details);

        return $growthFund;
    }


    /**
     * Get an account's trades from a specific subscription
     *
     * @param Account $account
     * @param Subscription $subscription
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function specificSubscriptionTrades($account, Subscription $subscription)
    {
        $trades = $account->specificCycleTrades($subscription->created_at, $subscription->ending_at)->get();
        return $trades;
    }

    /**
     * Get the account's trades from it's current subscription
     *
     * @param Account $account
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function currentSubscriptionTrades(Account $account)
    {

        $trades = $account->thisCycleTrades;
        return $trades;
    }

    /**
     * Check if an account has breached the daily drawdown rule
     *
     * @param Account $account
     * @param array $planRules
     * @return boolean
     */
    public function checkDailyLossLimitRule(Account $account, $planRules)
    {

        $margin = $this->margin($account);
        $metrics = $account->latestTwoMetrics;
        $yesterdayMetric = $metrics[1];
        $todayMetric = $metrics[0];

        $lastDayBalance = $yesterdayMetric->lastBalance;

        $currentBalance = $margin['currentBalance'];
        $currentEquity = $margin['currentEquity'];

        $startingBalance = $account->starting_balance;


        if ((($lastDayBalance - $currentEquity)  > ($planRules['DLL']['value'] / 100 *  $startingBalance)) && ($lastDayBalance > $currentEquity)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Find the news trades of an account for a specific subscription
     *
     * @param mixed $news
     * @param Account $account
     * @param Subscription $subscription
     * @return array
     */
    public function specificSubscriptionNewsCheck($news, Account $account, Subscription $subscription)
    {

        $trades = $this->specificSubscriptionTrades($account, $subscription);


        $newsTrades = [];

        $accountPackage = $account->plan->type;
        //! Run profit checker for express accounts only on Friday
        if (($accountPackage == Plan::EX_DEMO) || ($accountPackage == Plan::EX_REAL)) {
            $newsTimeThreshold = 300;
        } else {
            $newsTimeThreshold = 120;
        }


        $pairMap = [
            'USD' => ['XAUUSD', 'US30', 'NDX100', 'XAGUSD', 'USOUSD', 'SPX500'],
            'GBP' => ['UK100', 'USOUK'],
            'AUD' => ['XAUUSD', 'AUS200'],
            'JPY' => ['JPN225'],
        ];
        $mappedNewsTrades = [];

        //check for each $news['country'] string is in $trades['symbol'] string
        foreach ($news as $singleNews) {
            foreach ($trades as $trade) {

                if (str_contains($trade['symbol'], $singleNews['country'])) {
                    //check if $trade['open_time'] is between 120 seconds of $singleNews['timestamp']
                    if (($trade['open_time'] <= ($singleNews['timestamp'] + $newsTimeThreshold)) && ($trade['open_time'] >= ($singleNews['timestamp'] - $newsTimeThreshold))) {


                        $newsTrades[] = [
                            'trade_id' => $trade['id'],
                            'news_id' => $singleNews['id'],
                        ];
                        continue;

                        // $newsTrades[] = ['open', $trade['login'],$singleNews['country'], $trade['symbol'], $singleNews['title'], $trade['ticket'], $trade['open_time_str'], $singleNews['date']];
                    } else if (($trade['close_time'] <= $singleNews['timestamp'] + $newsTimeThreshold) && ($trade['close_time'] >= $singleNews['timestamp'] - $newsTimeThreshold)) {
                        // $newsTrades []= $trade['login'];

                        $newsTrades[] = [
                            'trade_id' => $trade['id'],
                            'news_id' => $singleNews['id'],
                        ];
                        continue;
                        // $newsTrades[] = ['close',$trade['login'], $singleNews['country'],$trade['symbol'], $singleNews['title'], $trade['ticket'], $trade['close_time_str'], $singleNews['date']];
                    }
                } else {

                    foreach ($pairMap as $currency => $symbolArray) {

                        if ($singleNews['country'] == $currency) {
                            if (in_array($trade['symbol'], $symbolArray)) {

                                if (($trade['open_time'] <= ($singleNews['timestamp'] + $newsTimeThreshold)) && ($trade['open_time'] >= ($singleNews['timestamp'] - $newsTimeThreshold))) {
                                    $newsTrades[] = [
                                        'trade_id' => $trade['id'],
                                        'news_id' => $singleNews['id'],
                                    ];

                                    // $mappedNewsTrades[] = ['open', $trade['login'], $singleNews['country'], $trade['symbol'], $singleNews['title'], $trade['ticket'], $trade['open_time_str'], $singleNews['date']];

                                    break;
                                } else if (($trade['close_time'] <= $singleNews['timestamp'] + $newsTimeThreshold) && ($trade['close_time'] >= $singleNews['timestamp'] - $newsTimeThreshold)) {
                                    $newsTrades[] = [
                                        'trade_id' => $trade['id'],
                                        'news_id' => $singleNews['id'],
                                    ];


                                    // $mappedNewsTrades[] =   ['close', $trade['login'], $singleNews['country'], $trade['symbol'], $singleNews['title'], $trade['ticket'], $trade['close_time_str'], $singleNews['date']];
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }

        return  $newsTrades;
    }

    /**
     * Find the news trades of an account for current subscription
     *
     * @param mixed $news
     * @param Account $account
     * @return array
     */
    public function checkNewsTrades($news, Account $account)
    {

        $trades = $this->currentSubscriptionTrades($account);


        $newsTrades = [];

        $accountPackage = $account->plan->type;
        //! Run profit checker for express accounts only on Friday
        if (($accountPackage == Plan::EX_DEMO) || ($accountPackage == Plan::EX_REAL)) {
            $newsTimeThreshold = 300;
        } else {
            $newsTimeThreshold = 120;
        }


        $pairMap = [
            'USD' => ['XAUUSD', 'US30', 'US30.i', 'NDX100', 'XAGUSD', 'USOUSD', 'SPX500'],
            'GBP' => ['UK100', 'USOUK'],
            'AUD' => ['XAUUSD', 'AUS200'],
            'JPY' => ['JPN225'],
        ];
        $mappedNewsTrades = [];

        //check for each $news['country'] string is in $trades['symbol'] string
        foreach ($news as $singleNews) {
            foreach ($trades as $trade) {

                if (str_contains($trade['symbol'], $singleNews['country'])) {
                    //check if $trade['open_time'] is between 120 seconds of $singleNews['timestamp']
                    if (($trade['open_time'] <= ($singleNews['timestamp'] + $newsTimeThreshold)) && ($trade['open_time'] >= ($singleNews['timestamp'] - $newsTimeThreshold))) {


                        $newsTrades[] = [
                            'trade_id' => $trade['id'],
                            'news_id' => $singleNews['id'],
                        ];
                        continue;

                        // $newsTrades[] = ['open', $trade['login'],$singleNews['country'], $trade['symbol'], $singleNews['title'], $trade['ticket'], $trade['open_time_str'], $singleNews['date']];
                    } else if (($trade['close_time'] <= $singleNews['timestamp'] + $newsTimeThreshold) && ($trade['close_time'] >= $singleNews['timestamp'] - $newsTimeThreshold)) {
                        // $newsTrades []= $trade['login'];

                        $newsTrades[] = [
                            'trade_id' => $trade['id'],
                            'news_id' => $singleNews['id'],
                        ];
                        continue;
                        // $newsTrades[] = ['close',$trade['login'], $singleNews['country'],$trade['symbol'], $singleNews['title'], $trade['ticket'], $trade['close_time_str'], $singleNews['date']];
                    }
                } else {

                    foreach ($pairMap as $currency => $symbolArray) {

                        if ($singleNews['country'] == $currency) {
                            if (in_array($trade['symbol'], $symbolArray)) {

                                if (($trade['open_time'] <= ($singleNews['timestamp'] + $newsTimeThreshold)) && ($trade['open_time'] >= ($singleNews['timestamp'] - $newsTimeThreshold))) {
                                    $newsTrades[] = [
                                        'trade_id' => $trade['id'],
                                        'news_id' => $singleNews['id'],
                                    ];

                                    // $mappedNewsTrades[] = ['open', $trade['login'], $singleNews['country'], $trade['symbol'], $singleNews['title'], $trade['ticket'], $trade['open_time_str'], $singleNews['date']];

                                    break;
                                } else if (($trade['close_time'] <= $singleNews['timestamp'] + $newsTimeThreshold) && ($trade['close_time'] >= $singleNews['timestamp'] - $newsTimeThreshold)) {
                                    $newsTrades[] = [
                                        'trade_id' => $trade['id'],
                                        'news_id' => $singleNews['id'],
                                    ];


                                    // $mappedNewsTrades[] =   ['close', $trade['login'], $singleNews['country'], $trade['symbol'], $singleNews['title'], $trade['ticket'], $trade['close_time_str'], $singleNews['date']];
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }

        return  $newsTrades;
    }

    /**
     * Update breached account's balance,equity and today's metric with latest values from margin
     *
     * @param Account $account
     * @return void
     */
    public function updateBreachedAccount($account)
    {
        $margin                            = $this->margin($account);
        $account->balance                  = $margin['currentEquity'];
        $account->equity                   = $margin['currentEquity'];
        $account->todayMetric->lastBalance = $margin['currentEquity'];
        $account->todayMetric->lastEquity  = $margin['currentEquity'];

        $account->unsetRelation('plan');
        $account->unsetRelation('accountRules');
        $account->unsetRelation('latestTwoMetrics');
        unset($account->lastDayMetric);

        $account->setRelation('todayMetric', $account->todayMetric);

        $account->push();
    }


    /**
     * get account profit percentage
     *
     * @param Account $account
     * @return float
     */
    public function getProfitPercentage(Account $account): float
    {
        $margin          = $this->margin($account);
        $startingBalance = $account->starting_balance;
        $accountProfit   = $margin["currentBalance"] - $startingBalance;
        return ($accountProfit / $startingBalance) * 100;
    }


    /**
     * phase migrate webhook
     *
     * @param Account $account
     * @param boolean $createNewAccount
     * @return void
     */
    public function phaseMigration(Account $account, bool $createNewAccount = true): void
    {
        Http::withHeaders([
            'Accept'             => 'application/json',
            'X-Verification-Key' => env('WEBHOOK_TOKEN'),
        ])->post(env('FRONTEND_URL') . "/api/v1/webhook/account-phase-migration", [
            "accountId"        => $account->id,
            "phaseId"          => null,
            "createNewAccount" => $createNewAccount,
        ]);
    }


    /**
     * account balance reset
     *
     * @param Account $account
     * @param mixed $margin
     * @return void
     */
    public function accountBalanceReset(Account $account, mixed $margin)
    {
        $accountStartingBalance = $account->starting_balance;

        ($accountStartingBalance <= $margin["currentBalance"]) ?
            $this->withdraw($account, ($margin["currentBalance"] - $accountStartingBalance)) :
            $this->deposit($account, ($accountStartingBalance - $margin["currentBalance"]));

        Redis::del('margin:' . $account->login);
        $account->beforeLatestMetric->delete();
        $account->latestMetric->delete();
        $account->save();
    }


    /**
     * new subscription create
     *
     * @param Account $account
     * @return void
     */
    public function newSubscriptionCreate(Account $account, $duration = null)
    {
        if($duration == null){
            $duration = $account->duration;
        }
        $subEnd = Helper::subend_days($duration);
        Subscription::create([
            'account_id' => $account->id,
            'login'      => $account->login,
            'plan_id'    => $account->plan_id,
            'ending_at'  => $subEnd['string'],
        ]);
        Cache::forget($account->id . ':firstTrade');
    }


    /**
     * account reset
     *
     * @param Account $account
     * @param mixed $margin
     * @return void
     */
    public function accountReset(Account $account, mixed $margin)
    {
        $tradeService  = new TradeService();
        $runningTrades = $tradeService->getRunningTrades($account);
        $batch         = [];
        foreach ($runningTrades as $runningTradeItem) {
            $batch[] = new TradeClose($account, $runningTradeItem);
        }

        if (sizeof($batch)) {
            Bus::batch(
                $batch
            )->then(function () {
            })->catch(function (Throwable $e) {
                Log::error($e);
            })->finally(function () use ($account, $margin) {
                $server = $account->server;
                $this->accountBalanceReset($account, $margin);
                $this->newSubscriptionCreate($account);
                $this->accountOn(
                    [
                        "login"        => $account->login,
                        "url"          => $server->url,
                        "sessionToken" => $server->login
                    ]
                );
            })->onQueue(AppConstants::QUEUE_TRADE_CLOSE_JOB)->dispatch();
        } else {
            $server = $account->server;
            $this->accountBalanceReset($account, $margin);
            $this->newSubscriptionCreate($account);
            $this->accountOn(
                [
                    "login"        => $account->login,
                    "url"          => $server->url,
                    "sessionToken" => $server->login
                ]
            );
        }
    }

    /**
     * Deposit profit to JL Backend
     *
     * @param Account $account
     * @param array $profitAmounts
     * @return void
     */
    public function clientUpdateProfit(Account $account, array $profitAmounts)
    {

        Http::withHeaders([
            'Accept' => 'application/json',
            'X-Verification-Key' => env('WEBHOOK_TOKEN'),
        ])->post(env('FRONTEND_URL') . "/api/v1/webhook/update-account-profit", [

            "accountId" => $account->id,
            "profit" => $profitAmounts['profit'] ?? 0,
            "withdrawableAmount" => $profitAmounts['withdrawableAmount'] ?? 0,
            "growthFund" => $profitAmounts['growthFundAmount'] ?? 0,
            "scaleUpFund" => $profitAmounts['scaleUpAmount'] ?? 0,
            "accumulatedProfit" => 0,
        ]);
    }

    /**
     * Make account read-only false and make account breach false
     *
     * @param Account $account
     * @return void
     */
    public function enableAccount(Account $account)
    {

        $server = $account->server;
        $url = $server->url;
        $sessionToken = $server->login;
        $enableRequest = Http::acceptJson()->post($url . "/user/update?token=" . $sessionToken, [
            'login' => $account->login,
            'read_only' => 0,
        ]);

        if ($enableRequest->successful()) {
            $account->update(['breached' => 0, 'breachedby' => null]);
        } else {
            Log::error($enableRequest->body());
        }
    }

    /**
     * Make account read-only false and make account breach false
     *
     * @param Account $account
     * @param RuleName $ruleName
     * @return void
     */
    public function removeRuleFromAccount(Account $account, RuleName $ruleName)
    {
        try {
            $accounRule=AccountRule::where('account_id',$account->id)->where('rule_id',$ruleName->id)->delete();
        } catch (Exception $th) {
            Log::error('Error',[$th]);
        }
    }
}
