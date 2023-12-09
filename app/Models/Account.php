<?php

namespace App\Models;

use DateTime;
use Carbon\Carbon;
use App\Models\Plan;
use \DateTimeInterface;
use App\Models\MtServer;
use App\Traits\Auditable;
use App\Models\AccountLabel;
use App\Traits\ProvideCacheKey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\AccountRulesService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;

class Account extends Model
{


    use SoftDeletes;
     //use Auditable;
    use HasFactory;
    use ProvideCacheKey;
    public const BREACHED_RADIO = [];

    public const PROFIT_TARGET_REACHED_APPROVAL = 1;
    public const MONTHEND_PARTIAL_PROFIT_SHARE_APPROVAL = 2;


    public const EV_P1 = 'Evaluation P1';
    public $table = 'accounts';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'customer_id',
        'login',
        'password',
        'type',
        'plan_id',
        'name',
        'comment',
        'balance',
        'equity',
        'credit',
        'breached',
        'breachedby',
        'trading_server_type',
        'created_at',
        'updated_at',
        'deleted_at',
        'parent_account_id',
        'starting_balance',
        'server_id'
    ];


    // public function getPlanAttribute()
    // {

    //     $plan = $this->plan()->first();
    //     $accountScaleUpRule = (new AccountRulesService($this))->getScaleUpRule();

    //     if ($accountScaleUpRule['data']['has_scaleup_rule']) {
    //         $plan->startingBalance = (float) $accountScaleUpRule['data']['value'];
    //     }

    //     return $plan;
    // }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function server()
    {
        return $this->belongsTo(MtServer::class, 'server_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'account_id');
    }

    public function breachEvents()
    {
        return $this->hasMany(BreachEvent::class, 'account_id');
    }

    public function getInvestorPasswordAttribute($value)
    {
        return $value ? Crypt::decrypt($value) : null;
    }

    public function getPasswordAttribute($value)
    {
        if (strlen($value . "") < 10) {
            return $value;
        } else {
            return Crypt::decrypt($value);
        }
    }

    public function getCachedAccountRulesAttribute($value)
    {
        return Cache::remember($this->cacheKey() . ':account_rules', 300, function () {
            return $this->accountRules;
        });
    }

    public function accountRules()
    {

        return $this->belongsToMany(RuleName::class, 'account_rules', 'account_id', 'rule_id')->withPivot('value');
    }
    // public function accountLabels()
    // {
    //     return $this->belongsToMany(Label::class,  'account_labels','account_id','id');
    // }

    public function accountLabels()
    {
        return $this->hasOne(AccountLabel::class, 'account_id');
    }

    public function breachEventsForApi()
    {
        return $this->hasOne(BreachEvent::class, 'account_id')->latest();
    }

    public function currentSubscription()
    {
        return $this->hasOne(Subscription::class)->latest('created_at');
    }
    public function firstSubscription()
    {

        return $this->hasOne(Subscription::class);
    }
    public function latestSubscription()
    {

        return $this->hasOne(Subscription::class)->latest('created_at');
    }
    public function subEndToday()
    {
        return $this->hasOne(Subscription::class)->whereDate('ending_at', Carbon::today());
    }

    public function metrics()
    {
        return $this->hasMany(AccountMetric::class);
    }

    public function lastDayMetric()
    {

        $yesterday = Carbon::yesterday()->format('Y-m-d');
        return $this->hasOne(AccountMetric::class)->whereDate('metricDate', '=', $yesterday)->latest();
    }

    public function specificCycleTrades($from, $to)
    {

        return $this->hasMany(Trade::class)->where('created_at', '>=', $from)->where('created_at', '<=', $to);
    }

    public function specificLastDayMetric($date)
    {
        // $yesterday = gmdate('Y-m-d', (strtotime('-1 day', strtotime($date))));
        $metricDate = new Carbon($date);
        $yesterday = $metricDate->subDay()->format('Y-m-d');
        return $this->hasOne(AccountMetric::class)->whereDate('metricDate', '=', $yesterday)->latest('created_at')->first();
    }

    public function specificMetric($date)
    {

        return $this->hasOne(AccountMetric::class)->whereDate('metricDate', '=', $date)->latest();
    }

    public function latestMetric()
    {
        return $this->hasOne(AccountMetric::class)->latest('id');
    }
    public function todayMetric()
    {
        $today = Carbon::today()->format('Y-m-d');
        return $this->hasOne(AccountMetric::class)->whereDate('metricDate', '=', $today)->latest();
    }

    public function beforeLatestMetric()
    {
        return $this->hasOne(AccountMetric::class)->orderBy('id', 'desc')->skip(1)->take(1);
    }
    public function latestTwoMetrics()
    {
        $today = Carbon::today()->format('Y-m-d H:i:s');
        $yesterday = Carbon::yesterday()->format('Y-m-d H:i:s');
        return $this->hasMany(AccountMetric::class)->whereIn('metricDate', [$today, $yesterday]);
    }
    public function allMetrics()
    {

        return $this->hasMany(AccountMetric::class)->orderBy('id', 'desc');
    }

    public function planRulesv2()
    {
        $plan = $this->plan;
        // return $plan;
        // $plan = Plan::with('planRule.ruleName')->find($plan->id);

        $planRules = $plan->rules;
        // return  $plan->rules;
        $getArray = [];

        foreach ($planRules as $rule) {

            $result['rule'] = $rule->name;

            $result['condition'] = $rule->condition;
            $result['value'] = $rule->pivot->value;
            $result['is_percent'] = $rule->is_percent;
            $getArray[$rule->condition] = $result;
        }

        $planRules = collect($getArray);

        // return $planRules;
        $accountRules = $this->accountRules; // ! Get the specific account rules for the account
        $accountRules = $accountRules->map(function ($rule) {
            $rule->rule = $rule->name;
            $rule->value = $rule->pivot->value;
            $rule->is_accountRule = true;
            return collect($rule)->forget(['pivot', 'id', 'created_at', 'updated_at', 'deleted_at', 'name']);
        });

        $accountRules = $accountRules->keyBy('condition'); // ! Map the rules by condition
        return $accountRules->union($planRules); //! Merge the plan rules and account rules

    }

    public function planRules()
    {
        $plan = $this->plan;
        // return $plan;
        // $plan = Plan::with('planRule.ruleName')->find($plan->id);

        $planRules = $plan->cached_rules;
        // return  $plan->rules;
        $getArray = [];

        foreach ($planRules as $rule) {

            $result['rule'] = $rule->name;

            $result['condition'] = $rule->condition;
            $result['value'] = $rule->pivot->value;
            $result['is_percent'] = $rule->is_percent;
            $getArray[$rule->condition] = $result;
        }

        $planRules = collect($getArray);

        $accountRules = $this->cached_account_rules; // ! Get the specific account rules for the account
        $accountRules = $accountRules->map(function ($rule) {
            $rule->rule = $rule->name;
            $rule->value = $rule->pivot->value;
            $rule->is_accountRule = true;
            return collect($rule)->forget(['pivot', 'id', 'created_at', 'updated_at', 'deleted_at', 'name']);
        });

        $accountRules = $accountRules->keyBy('condition'); // ! Map the rules by condition
        return $accountRules->union($planRules); //! Merge the plan rules and account rules

    }

    public function tradingDays()
    {
        $thisMonthMetric = $this->thisCycleMetrics;
        return $thisMonthMetric->where('isActiveTradingDay', 1)->count();
    }

    public function growthFund()
    {
        return $this->hasMany(GrowthFund::class, 'account_id', 'id');
    }

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }
    public function thisCycleTrades()
    {
        $this->loadMissing('currentSubscription');
        $currentSubscription = $this->currentSubscription;
        return $this->hasMany(Trade::class)->where('created_at', '>=', $currentSubscription->created_at);
    }

    public function thisCycleMetrics()
    {
        $this->loadMissing('currentSubscription');
        $currentSubscription = $this->currentSubscription;
        $createdAt = $currentSubscription->created_at->format('Y-m-d');
        return $this->hasMany(AccountMetric::class)->whereDate('created_at', '>=', $createdAt);
    }

    public function uptoDateTrades($date)
    {
        $currentSubscription = $this->latestSubscription;
        return $this->hasMany(Trade::class)->where('created_at', '>=', $currentSubscription->created_at)->whereDate('updated_at', '<=', $date)->get();
    }
    public function todayTrades()
    {
        $currentSubscription = $this->latestSubscription;

        return $this->hasMany(Trade::class)->whereDate('created_at', '=', Carbon::today());
    }

    public function isFirstTrade()
    {
        $seconds = 1123200; //13 days
        return Cache::remember($this->id . ':firstTrade', $seconds, function () {
            $tradeCount = $this->thisCycleTrades;
            return  $tradeCount->count() == 0 ? true : false;
        });
    }

    public function closeRunningTrades()
    {
        try {
            $account = $this;
            // dd($account);
            $activeTrades = [];

            $login = $account->login;
            //!get all active trades for that account
            $loginTrades = Redis::smembers('orders:' . $login . ':working');

            if ($loginTrades != null) {
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


            $tradesReport = $tradesReport['data'];

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
            }
        } catch (\Exception $e) {

            return $e;
        }
        return true;
    }

    public function isConsistent($deviation)
    {
        $account = $this;
        $accountID = $account->id;
        if ($account != null) {

            $lastFriday = Carbon::createFromTimeStamp(strtotime("last Friday", Carbon::now()->timestamp))->toDateString();

            $checkSubscription = $account->latestSubscription; // get the lastest subs
            $subsStart = $checkSubscription->created_at;

            $joinDateDiff = (new DateTime($subsStart))->diff(Carbon::createFromTimeStamp(strtotime("last Friday", Carbon::now()->timestamp)))->days;

            //$joinDateDiff = $joinDateDiff == 0 ? 1 : $joinDateDiff;

            //Get All Trade last Friday and subscription wise
            $thisweekTrades = Trade::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(volume) as lots'), DB::raw('count(volume) as trade_count'))
                ->whereAccountId($accountID)
                ->where('created_at', '>=', $checkSubscription->created_at)
                ->groupBy('date')
                ->get();

            $overallTrades = collect();
            if ($joinDateDiff >= 1) {
                $overallTrades = Trade::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(volume) as lots'), DB::raw('count(volume) as trade_count'))
                    ->whereAccountId($accountID)
                    ->whereDate('created_at', '<=', $lastFriday)
                    ->where('created_at', '>=', $checkSubscription->created_at)
                    ->groupBy('date')
                    ->get();
                //Get All Trade last Friday and subscription wise
                $thisweekTrades = Trade::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(volume) as lots'), DB::raw('count(volume) as trade_count'))
                    ->whereAccountId($accountID)
                    ->where('created_at', '>=', $checkSubscription->created_at)
                    ->whereDate('created_at', '>', $lastFriday)
                    ->groupBy('date')
                    ->get();
            } else {

                return false;
            }

            if (!$thisweekTrades->isEmpty()) {
                $thisWeekTotalLots = $thisweekTrades->sum("lots") / 100;
                $thisWeekTotaltrades = $thisweekTrades->sum("trade_count");
                $thisWeekActiveTradingDay = $thisweekTrades->count();

                $thisweekTrades = [

                    "totalLots" => $thisWeekTotalLots,
                    "totaltrades" => $thisWeekTotaltrades,
                    "activeTradingDay" => $thisWeekActiveTradingDay,

                    'avTrade' => $thisWeekTotaltrades / ($thisWeekActiveTradingDay == 0 ? 1 : $thisWeekActiveTradingDay),
                    'avLot' => $thisWeekTotalLots / ($thisWeekActiveTradingDay == 0 ? 1 : $thisWeekActiveTradingDay),

                ];
            } else {
                $thisweekTrades = false;
            }

            if (!$overallTrades->isEmpty()) {

                $overallTotalLots = $overallTrades->sum("lots") / 100;
                $overallTotaltrades = $overallTrades->sum("trade_count");
                $overallActiveTradingDay = $overallTrades->count();

                $overallAvgTotalLots = round($overallTotalLots / $overallActiveTradingDay, 2);
                $overallAvgTotaltrades = round($overallTotaltrades / $overallActiveTradingDay, 2);
                //$multiple = ($joinDateDiff >= 2) ? 1 : (5 / $overallActiveTradingDay);

                $overallTrades = [
                    "debug" => [
                        "lastFriday" => $lastFriday,
                        // "checkSubscription" => $checkSubscription
                    ],
                    "totalLots" => $overallAvgTotalLots,
                    "totaltrades" => $overallAvgTotaltrades,
                    "activeTradingDay" => $overallActiveTradingDay,
                    "lots_upper_limit" => upperLimit($overallTotalLots, $overallActiveTradingDay, $deviation),
                    "lots_lower_limit" => lowerLimit($overallTotalLots, $overallActiveTradingDay, $deviation),
                    "trades_upper_limit" => upperLimit($overallTotaltrades, $overallActiveTradingDay, $deviation),
                    "trades_lower_limit" => lowerLimit($overallTotaltrades, $overallActiveTradingDay, $deviation),
                ];
            } else {
                $overallTrades = false;
            }

            if ($thisweekTrades) {

                if (($overallTrades["trades_lower_limit"] > $thisweekTrades["avTrade"]) || ($thisweekTrades["avTrade"] > $overallTrades["trades_upper_limit"])) {
                    return false;
                }
                if (($overallTrades["lots_lower_limit"] > $thisweekTrades["avLot"]) || ($thisweekTrades["avLot"] > $overallTrades["lots_upper_limit"])) {
                    return false;
                }
            } else {
                return false;
            }

            return true;
        } else {
            return response()->json(['message' => 'Account Id Not valid'], 404);
        }
    }

    public function parentAccount()
    {
        return $this->belongsTo(Account::class, 'parent_account_id');
    }

    public function accountAccountCertificates()
    {
        return $this->hasMany(AccountCertificate::class, 'account_id', 'id');
    }

}
function upperLimit(float $int, int $day, $deviation)
{

    return round($int / $day * $deviation, 2);
}
function lowerLimit(float $int, int $day, $deviation)
{
    return round($int / $day / $deviation, 2);
}
