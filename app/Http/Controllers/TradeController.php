<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Jobs\DiscordAlertJob;
use App\Models\Plan;
use DateTime;
use Carbon\Carbon;
use App\Models\Trade;
use App\Helper\Helper;
use App\Models\Account;
use App\Models\TradeSlTp;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\AccountMetric;
use App\Models\ArbitraryTrade;
use App\Services\RulesService\DurationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;


class TradeController extends Controller
{

    public function riskManagement()
    {

        $planType = 'Express Demo';
        $accounts =  Account::select(['created_at', 'id'])->with(['plan'])
            ->whereHas('plan', function ($q) use ($planType) {

                $q->where('type', '=', $planType);
            })

            ->get();
        // return $accounts;
        $deviation = 0;
        $pnlAccounts = $accounts->map(function ($account) {
            $startDate = $account->created_at->format('Y-m-d');
            // dump($startDate);
            $endDate = date('Y-m-d', strtotime($account->created_at->format('Y-m-d') . " +3 days"));
            // dump($endDate);
            $trades = Trade::where('account_id', $account->id)->whereBetween('created_at', [$startDate, $endDate])->get();
            $account['pnl'] = $trades->sum('profit');
            $account['trades'] = count($trades);
            return $account;
        });


        // return $pnlAccounts;

        $pnlAccounts = $pnlAccounts->filter(function ($account) {

            return  $account['trades'] > 0;
        });

        // return $pnlAccounts;
        $mean = $pnlAccounts->sum('pnl') / count($pnlAccounts);
        $numerator = 0;
        foreach ($pnlAccounts as $account) {

            $numerator = $numerator + pow($account['pnl'] - $mean, 2);
        }
        return json_encode($pnlAccounts);
        $deviation = sqrt($numerator / count($pnlAccounts));

        return ["Accounts : " . count($pnlAccounts), "Average : " . $mean, "Deviation : " . $deviation, "PnL : " . $pnlAccounts->sum('pnl'), $pnlAccounts];


        return $pnlAccounts->sum('pnl');
    }
    public function eaStats()
    {

        $from = date('2022-04-25');
        $to = date('2022-04-26');

        $trades = Trade::groupBy('login')->whereBetween('created_at', [$from, $to])->where('reason', 1)->select('login', DB::raw('count(*) as eaTradeCount'))->get();

        return $trades;

        $trades = $trades->where('eaTradeCount', '>=', 10);
        // return $trades->values();
        $accounts = $trades->pluck('login');




        $profitAccounts = $accounts->filter(function ($login) {
            $account = Account::whereLogin($login)->first();
            return $account->balance > $account->starting_balance;
        });
        $lossAccounts = $accounts->filter(function ($login) {
            $account = Account::whereLogin($login)->first();
            return $account->balance <= $account->starting_balance;
        });

        $violationAccounts = $accounts->filter(function ($login) {
            $account = Account::whereLogin($login)->first();
            return str_contains($account->breachedby, 'Loss');
        });

        return ["Total EA Trade Accounts :" . count($accounts), "In profit: " . count($profitAccounts), "In loss: " . count($lossAccounts), "Violation: " . count($violationAccounts)];


        return count($trades);
    }

    public function receiveTrade(Request $request)
    {
        $receivedTrade = $request->input('object');
        if (($request->input('type') == 'user') || ($request->input('type') == 'symbol')) {
            return $request->input('type');
        }

        if (($receivedTrade['type'] > 1)) {
            return 'type ' . $receivedTrade['type'];
        }

        if ((!isset($receivedTrade['ticket'])) || (!isset($receivedTrade['login']))) {
            return response()->json("Ticket/Login missing", 500);
        }

        $account = Account::where('login', '=', $receivedTrade['login'])->first();
        if ($account == null) {
            return response()->json(["message" => "This login number $receivedTrade[login] is not registered account"], 500);
        }
        // return $account;
        $receivedTrade['account_id'] = $account->id;
        if ($receivedTrade['swap'] < 0) {
            $receivedTrade['swap'] = 0;
        } else if ($receivedTrade['swap'] > 2047483647) {
            $receivedTrade['swap'] = 0;
        }
        $receivedTrade['swap'] = round($receivedTrade['swap'], 2);

        if ($account->isFirstTrade()) {
            // Log::info("first trade true paise");
            $sub = $account->latestSubscription;
            $getPlanDuration = $account->duration;
            $subend = Helper::subend_days($getPlanDuration)['string'];
            $substart = strtotime("now");
            $sub_start_time = date('Y-m-d H:i:s', $substart);
            $sub->update(['ending_at' => $subend, 'created_at' => $sub_start_time]);
            Cache::forget($account->id . ':firstTrade');
        }

        $tradeExist = Trade::whereTicket($receivedTrade['ticket'], $account->id)->first();

        $oldSl = 0;
        $oldTp = 0;
        $tradeInfo = (object)[];
        $newTrade = (object)[];

        if ($tradeExist != null) {
            $oldSl = $tradeExist->sl;
            $oldTp = $tradeExist->tp;
            $tradeInfo =  $tradeExist->update($receivedTrade);
            $tradeInfo = $tradeExist;
            // $tradeInfo = $tradeExist->fresh();
        } else {
            $tradeInfo = Trade::create($receivedTrade);

            $new_metric = $account->latestMetric;
            $new_metric->trades += 1;
            $new_metric->isActiveTradingDay = true;
            $account->push();

            $this->alertSendToTradeTeam($account);
        }

        $slTpInsert = [];
        if ($receivedTrade['sl']  != $oldSl) {

            $slTpInsert[] = [
                'type' => 1,
                'value' => $receivedTrade['sl'],

            ];
        }

        if ($receivedTrade['tp']  != $oldTp) {
            $slTpInsert[] = [
                'type' => 2,
                'value' => $receivedTrade['tp'],

            ];
        }

        if (count($slTpInsert) > 0) {

            $tradeInfo->tradeSlTp()->createMany($slTpInsert);
        }

        //! Check for trade arbitration
        if ($receivedTrade['close_time'] != 0) {

            $time_difference = $receivedTrade['close_time'] - $receivedTrade['open_time'];
            if ($time_difference < 30) {
                $arbitraryTrade = ArbitraryTrade::create(
                    [
                        'account_id' => $account->id,
                        'login' => $receivedTrade['login'],
                        'ticket' => $receivedTrade['ticket'],
                        'time_difference' => $time_difference,

                    ]
                );

                // Helper::discordAlert("**Arbitrary Trade**:\nAccntID : " . $account->id . "\nticket : " . $receivedTrade['ticket'] . "\nTime Diff : " . $time_difference);
            }
        }

        return $tradeInfo;
    }


    public function tradeSyncAccount(int $id)
    {
        $telescope = [];
        $account = Account::find($id);
        $login = $account->login;
        $server = $account->server;
        $url = $server->url;
        $sessionToken = $server->login;
        $reportTrades = Http::acceptJson()->post($url . "/users/report?token=" . $sessionToken, [

            'logins' => [$login],
            "from" => Carbon::now()->subHours(200)->format('Y.m.d H:i'),
            "to" => Carbon::now()->addHours(24)->format('Y.m.d H:i'),
            "types" => "0,1"

        ]);
        $reportTrades = json_decode($reportTrades, 1);

        // $telescope[] = "All closed Trades:  $reportTrades;

        $reportTrades = collect($reportTrades);
        $accountTrades = $reportTrades;
        $workingTrades = Redis::smembers('orders:' . $login . ':working');

        if ($workingTrades != null) {
            $telescope[] = "Working Trade Count: " . count($workingTrades) . "";

            $workingTrades = array_map('intval',  $workingTrades);

            $workingtradeReports = Http::acceptJson()->post($url . "/trades/report?token=" . $sessionToken,  ['orders' => $workingTrades]);
            $accountTrades = $accountTrades->toArray();
            array_push($accountTrades,  ...$workingtradeReports['data']);

            // $telescope[] = $accountTrades;
        }


        foreach ($accountTrades as $report) {

            // $telescope[] = "Trade Ticket- $report[ticket]";

            $report['account_id'] = $account->id;
            if ($report['swap'] < 0) {
                $report['swap'] = 0;
            } else if ($report['swap'] > 2047483647) {
                $report['swap'] = 0;
            }
            $report['swap'] = round($report['swap'], 4);
            $report['created_at'] = Carbon::createFromFormat('Y.m.d H:i:s', $report['open_time_str'])->format('Y-m-d H:i:s');


            $update = Trade::updateOrCreate([
                'ticket' => $report['ticket']

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

        return $telescope;
    }
    public function showTradesOfAccount(int $account, Request $request)
    {

        $validatedData = $request->validate([
            'page' => 'nullable|integer',
        ]);

        $account = Account::findOrFail($account);
        if ($account == null) {
            return response()->json(['message' => "This account doesn't exist, Trades not available"], 404);
        }

        $trades = $account->trades;

        if ($request->has('page')) {

            $page = $validatedData['page'];
            //$trades = $trades->skip($page*15)->take(15);
            $trades = $trades->skip($page * 30)->take(30);
        }

        foreach ($trades as $trade) {
            if ($trade->close_time == 0) {
                $pl = Redis::get('orderpl:' . $trade->ticket);
                if ($pl == null) {
                    continue;
                }

                $trade->profit = $pl;
            }

            $trade->sl = round($trade->sl, 4);
            $trade->tp = round($trade->tp, 4);
            $trade->open_price = round($trade->open_price, 4);
            $trade->close_price = round($trade->close_price, 4);
            $trade->commission = round($trade->commission, 4);
            $trade->lots = ($trade->volume / 100);
            $liveTrades[] = $trade;
        }
        $closeTrades = collect($trades)->where('close_time', 0)->all();
        $runningTrades = collect($trades)->where('close_time', '!=', 0)->all();

        return response()->json([
            'open_trades' => $runningTrades,
            'closed_trades' => $closeTrades,
            'total_trades' => count($account->trades),
        ]);

        return response()->json(isset($liveTrades) ? $liveTrades : ["error" => "No Trades available"]);
    }

    //
    public function allTrades(int $accountID)
    {

        $account = Account::find($accountID);

        $trades = $account->thisCycleTrades;
        $trades = $trades->map(function ($trade) {
            $trade->makeHidden('created_at');
            $trade->makeHidden('deleted_at');
            $trade->makeHidden('updated_at');
            return $trade;
        });


        return $trades;
    }

    public function consistencyRule(int $accountID)
    {

        $account = Account::find($accountID);
        if ($account != null) {

            $lastFriday = Carbon::createFromTimeStamp(strtotime("last Friday", Carbon::now()->timestamp))->toDateString();

            $checkSubscription = $account->latestSubscription; // get the lastest subs
            $subsStart = $checkSubscription->created_at;

            $controller = new \App\Http\Controllers\AccountController();
            $allPlanRule = $controller->accountPlanRules($account->id);
            $deviation = $allPlanRule['CRD']['value'] ?? '2.5';

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

            ret:
            return $result = [

                "trade" => [
                    "avg_trade" => $overallTrades['totaltrades'] ?? null,
                    "weekly_average" => $thisweekTrades["avTrade"] ?? null,
                    "overall_average" => $overallAvgTotaltrades ?? null,
                    "low" => $overallTrades["trades_lower_limit"] ?? null,
                    "high" => $overallTrades["trades_upper_limit"] ?? null,
                ],
                "lot" => [
                    "avg_lot" => $overallTrades['totalLots'] ?? null,
                    "weekly_average" => $thisweekTrades["avLot"] ?? null,
                    "overall_average" => $overallAvgTotalLots ?? null,
                    "low" => $overallTrades["lots_lower_limit"] ?? null,
                    "high" => $overallTrades["lots_upper_limit"] ?? null,
                ],
                "allInfo" => $overallTrades,
            ];
        } else {
            return response()->json(['message' => 'Account Id Not valid'], 404);
        }
    }

    protected function alertSendToTradeTeam(Account $account)
    {
        if ($account->plan->type == Plan::EV_REAL &&
            ($account->plan->startingBalance == 100000 || $account->plan->startingBalance == 200000)) { // 100k/200k
            Helper::discordAlert("**New trade open User**:\nLogin : " . $account->login  .' Balance:'. $account->plan->startingBalance);
        }
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
