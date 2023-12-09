<?php

namespace App\Services;

use App\Constants\AppConstants;
use App\Models\Account;
use App\Models\AccountMetric;
use App\Models\Trade;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * This class represents an AccountServiceV2 class with an Account object.
 */
class TradingOverviewService
{

    public $account;
    public $planRules;

    /**
     * Constructs a new instance of the AccountServiceV2 class.
     *
     * @param Account $account The account associated with this service.
     * @param bool $loadPlanRules Whether to load the plan rules for the account or not.
     */
    public function __construct(Account $account, bool $loadPlanRules = true)
    {
        $this->account = $account;
        if ($loadPlanRules)
            $this->planRules = $this->account->planRules()->toArray();
    }


    /**
     * Get account information and trading statistics.
     *
     * This function returns an array containing account details, trading stats,
     * trading cycle details, and trading objectives (trading days, daily loss,
     * overall loss, and profit) for the account. It also checks if any rules
     * have been violated or passed, as well as calculates max daily loss and max
     * overall loss based on the starting balance of the account.
     *
     * @return array An array containing account information and trading stats.
     * @throws Exception If there is an error while fetching the data.
     */
    public function detailsStatus(): array
    {
        try {
            $accountService = new AccountOverviewService($this->account);
            $balance        = $accountService->getBalance();
            $tradeInfo      = $this->tradeInfo();
            return [
                'stats' => [
                    'equity'        => $balance->current_equity,
                    'balance'       => $balance->current_balance,
                    'profitability' => 'It will show the win-loss percentage ',
                    'average_wing'  => $tradeInfo ? $tradeInfo->average_winning_trade : 0,
                    'average_loss'  => $tradeInfo ? $tradeInfo->average_losing_trade : 0,
                    'trades'        => $tradeInfo ? $tradeInfo->trades : 0,
                    'lots'          => $tradeInfo ? $tradeInfo->lots : 0,
                    'average_rrr'   => $tradeInfo ? $tradeInfo->average_rrr : 0, // need to formula
                    'win_rate'      => $tradeInfo ? $tradeInfo->win_rate : 0,
                    'loss_rate'     => $tradeInfo ? $tradeInfo->win_rate : 0,
                    'profit_factor' => $tradeInfo ? $tradeInfo->profit_factor : 0,
                    'best_trade'    => $tradeInfo ? $tradeInfo->best_trade : 0,
                    'worst_trade'   => $tradeInfo ? $tradeInfo->worst_trade : 0,
                    'long_won'      => $tradeInfo ? $tradeInfo->long_won : 0,
                    'short_won'     => $tradeInfo ? $tradeInfo->short_won : 0,
                    'gross_profit'  => $tradeInfo ? $tradeInfo->gross_profit : 0,
                    'gross_loss'    => $tradeInfo ? $tradeInfo->gross_loss : 0,
                ],
            ];
        } catch (Exception $exception) {
            Log::error("Account overview service error ", [$exception]);
            throw $exception;
        }
    }


    /**
     * Retrieves the trade info.
     * @return object|null
     */
    public function tradeInfo(): ?object
    {
        $trades_cycle = $this->account->thisCycleTrades;
        if ($trades_cycle != null) {
            $losing_trades        = $trades_cycle->where('profit', '<', 0)->where('close_time', '!=', 0);
            $winning_trades       = $trades_cycle->where('profit', '>=', 0)->where('close_time', '!=', 0);
            $winning_trades_count = $winning_trades->count();
            $losing_trades_count  = $losing_trades->count();
            $totalTrades          = $trades_cycle->count();
            $grossProfit          = $winning_trades->sum('profit');
            $grossLoss            = $losing_trades->sum('profit');
            return (object)[
                'average_winning_trade' => round($winning_trades->avg('profit') != null ? $winning_trades->avg('profit') : 0, 2), //! upto that date,
                'average_losing_trade'  => round($losing_trades->avg('profit') != null ? $losing_trades->avg('profit') : 0, 2), //! upto that date,
                'trades'                => $totalTrades,
                'lots'                  => $trades_cycle->sum('volume') / 100,
                'average_rrr'           => 0,
                'win_rate'              => round((($winning_trades_count / $totalTrades) * 100), 2),
                'loss_rate'             => round((($losing_trades_count / $totalTrades) * 100), 2),
                'best_trade'            => round($trades_cycle->max('profit'), 2),
                'worst_trade'           => round($trades_cycle->min('profit'), 2),
                'profit_factor'         => round($grossProfit / $grossLoss, 2),
                'long_won'              => round(($trades_cycle->where('type', 1)->sum('profit') / 100), 2),
                'short_won'             => round(($trades_cycle->where('type', 0)->sum('profit') / 100), 2),
                'gross_profit'          => round($grossProfit, 2),
                'gross_loss'            => round($grossLoss, 2),
            ];
        }
        return null;
    }

    /**
     * @param $request
     * @param $id
     * @return Response
     */
    public function accountGrowthStatus($request)
    {
        if ($request->start_date && $request->end_date) {
            $getAccountGrowth = AccountMetric::select("metricDate", "lastBalance", "lastEquity")->whereAccountId($request->account_id)->whereBetween('metricDate', [$request->start_date, $request->end_date])->get();
        } else {
            $getAccountGrowth = AccountMetric::select("metricDate", "lastBalance", "lastEquity")->whereAccountId($request->account_id)->get();
        }
        return $getAccountGrowth;
    }

    /**
     * symbol graph api result
     * @return array
     */
    public function symbolPerformance(): array
    {
        $trades_cycle = $this->account->thisCycleTrades;
        $totalTrade   = $trades_cycle->count();
        $symbolInfo   = $trades_cycle->groupBy("symbol");
        $arrays       = [];
        foreach ($symbolInfo as $key => $item) {
            $profit = 0;
            $loss   = 0;
            $lot    = 0;
            foreach ($item as $row) {
                $lot += $row->volume;
                if ($row->profit >= 0 && $row->close_time != 0) {
                    $profit += $row->profit;
                } elseif ($row->profit < 0 && $row->close_time != 0) {
                    $loss += $row->profit;
                }
            }
            $arrays [$key] = [
                'symbol_percentage' => count($item) / $totalTrade * 100,
                'lot'               => $lot / 100,
                'no_of_trade'       => count($item),
                'profit'            => $profit + $loss,
            ];
        }
        return $arrays;
    }


    /**
     * weekly profit loss return by date
     * @return array
     */
    public function weeklyProfitLoss(): array
    {
        $trades_cycle = $this->account->thisCycleTrades;
        $weeks        = [];
        foreach ($trades_cycle as $key => $row) {
            if (isset($weeks[date('l', strtotime($row->created_at))])) {
                $weeks[date('l', strtotime($row->created_at))] = round($weeks[date('l', strtotime($row->created_at))] + $row->profit, 2);
            } else {
                $weeks[date('l', strtotime($row->created_at))] = round($row->profit, 2);
            }
        }
        return self::weekFormatting($weeks);
    }

    /**
     * average profit loss return
     * @return array
     */
    public function averageProfitLoss(): array
    {
        $trades_cycle = $this->account->thisCycleTrades;
        $wing         = 0;
        $loss         = 0;
        if ($trades_cycle != null) {
            $losing_trades        = $trades_cycle->where('profit', '<', 0)->where('close_time', '!=', 0);
            $winning_trades       = $trades_cycle->where('profit', '>=', 0)->where('close_time', '!=', 0);
            $totalTrades          = $trades_cycle->count();
            $winning_trades_count = $winning_trades->count();
            $losing_trades_count  = $losing_trades->count();
            $wing                 = round((($winning_trades_count / $totalTrades) * 100), 2);
            $loss                 = round((($losing_trades_count / $totalTrades) * 100), 2);
        }
        return [
            'win_rate'  => $wing,
            'loss_rate' => $loss,
        ];
    }


    /**
     * @param array $weeks
     * @return array
     */
    public function weekFormatting(array $weeks): array
    {
        $newArray = [];
        foreach (weekName() as $key => $value) {
            $newArray[$value] = $weeks[$value] ?? 0;
        }
        return $newArray;
    }

    /**
     * buy sell order result
     * @return array
     */
    public function buySellOrderType(): array
    {
        $trades_cycle = $this->account->thisCycleTrades;
        $buySell      = [];
        if ($trades_cycle != null) {
            $winning_trades      = $trades_cycle->where('profit', '>=', 0)->where('close_time', '!=', 0);
            $winning_trades_buy  = $winning_trades->where('type_str', AppConstants::buy)->count();
            $winning_trades_sell = $winning_trades->where('type_str', AppConstants::sell)->count();
            $totalBuyTrades      = $winning_trades->count();
            $buySell['buy']      = [
                'profit'    => round($trades_cycle->where('type_str', AppConstants::buy)->sum('profit'), 2),
                'lot'       => $trades_cycle->where('type_str', AppConstants::buy)->sum('volume') / 100,
                'trade'     => $trades_cycle->where('type_str', AppConstants::buy)->count(),
                'win_ratio' => round((($winning_trades_buy / $totalBuyTrades) * 100), 2),
            ];
            $buySell['sell']     = [
                'profit'    => round($trades_cycle->where('type_str', AppConstants::sell)->sum('profit'), 2),
                'lot'       => $trades_cycle->where('type_str', AppConstants::sell)->sum('volume') / 100,
                'trade'     => $trades_cycle->where('type_str', AppConstants::sell)->count(),
                'win_ratio' => round((($winning_trades_sell / $totalBuyTrades) * 100), 2),

            ];
        }
        return $buySell;
    }

    /**
     * weekly profit loss return by date
     * @return array
     */
    public function hourlyProfitLoss(): array
    {
        $trades_cycle = $this->account->thisCycleTrades;
        $hourly       = [];
        foreach ($trades_cycle as $key => $row) {
            if (isset($hourly[date('H', strtotime($row->created_at))])) {
                $hourly[date('H', strtotime($row->created_at))] = round($hourly[date('H', strtotime($row->created_at))] + $row->profit, 2);
            } else {
                $hourly[date('H', strtotime($row->created_at))] = round($row->profit, 2);
            }
        }
        return self::hourFormatting($hourly);
    }

    /**24 hour formatting
     * @param array $hourly
     * @return array
     */
    public function hourFormatting(array $hourly): array
    {
        $hours = [];
        $iTimestamp = mktime(0,0,0,1,1,2023);
        for ($i = 1; $i <=24; $i++) {
            $hour = date('H', $iTimestamp);
            $hours[$hour] = $hourly[$hour] ?? 0;
            $iTimestamp += 3600;
        }
        ksort($hours);
        return $hours;

//        for ($i = 0; $i < 24; $i++) {
//            $hours[$i] = $hourly[$i] ?? 0;
//        }
//        return $hours;
    }


}
