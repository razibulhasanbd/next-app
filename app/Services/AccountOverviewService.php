<?php

namespace App\Services;

use App\Constants\AppConstants;
use App\Http\Controllers\TradeController;
use App\Models\Account;
use App\Models\Plan;
use App\Models\Subscription;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

/**
 * This class represents an AccountServiceV2 class with an Account object.
 */
class AccountOverviewService
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
    public function accountInfo(): array
    {
        try {
            $tradingCycle  = $this->tradingCycle();
            $balance       = $this->getBalance($this->account);
            $profitTarget  = $this->profitTarget($balance->current_balance);
            $rulesData     = $this->getAccountRules();
            $lossThreeHold = $this->lossThreeHold($balance->current_equity);
            $manager       = $this->managerInfo();

            $mtdTargetValue  = (int)($rulesData['mtd_target_value'] ?? 0);
            $thisMonthMetric = $this->account->thisCycleMetrics;

            $maxMonthlyLoss     = $thisMonthMetric->min('maxMonthlyLoss');
            $activeTradingDay   = $thisMonthMetric->where('isActiveTradingDay', 1)->count();
            $allowedDailyLoss   = (float)$this->calculateDailyMaxLossByRuleOf($this->account->starting_balance, $rulesData['dll_target_value'] ?? 0);
            $allowedMonthlyLoss = (float)$this->calculateDailyMaxLossByRuleOf($this->account->starting_balance, $rulesData['mll_target_value'] ?? 0);
            $tradingDays        = [];
            if ($this->account->plan->type != Plan::EV_REAL) {
                $tradingDays = [
                    'label'         => 'Minimum ' . config('settings.trading.trading_days') . ' Trading Days',
                    'label_static'  => $rulesData['mtd_name'] ?? '',
                    'target_label'  => $rulesData['mtd_target_label'] ?? '',
                    'target_value'  => $mtdTargetValue ?? '',
                    'current_label' => 'Current Result',
                    'current_value' => $activeTradingDay,
                    'is_visible'    => $rulesData['is_mtd_visible'] ?? false,
                    'status'        => $this->hasViolatedTradingDaysRule($activeTradingDay, $mtdTargetValue) ? AppConstants::ON_GOING : AppConstants::PASSED
                ];
            }

            $profit = [];
            if (in_array($this->account->plan->type, [Plan::EV_P1, Plan::EV_P2, Plan::EX_DEMO])) {
                $profit = [
                    'label'                   => '',
                    'label_static'            => $rulesData['pt_name'] ?? '',
                    'target_label'            => $rulesData['pt_target_label'] ?? '',
                    'target_value'            => '$' . number_format($profitTarget->profit_target, 2),
                    'target_value_percentage' => $rulesData['pt_target_value'] ?? 0,
                    'current_label'           => 'Current Result',
                    'current_value'           => '$' . number_format((float)$profitTarget->profit_target_reached, 2),
                    'is_visible'              => $rulesData['is_pt_visible'] ?? false,
                    'status'                  => ($this->hasHitProfitTargetRule($profitTarget->profit_target, ((float)$profitTarget->profit_target_reached))) ? AppConstants::PASSED : AppConstants::ON_GOING
                ];
            }

            $consistency = [];
            if (in_array($this->account->plan->type, [Plan::EX_DEMO, Plan::EX_REAL])) {
                $trade              = new TradeController();
                $consistencyData    = $trade->consistencyRule($this->account->id);
                $consistency = [
                    'trade'                   => [
                        'count'           => $consistencyData['trade']['avg_trade'],
                        'weekly_average'  => $consistencyData['trade']['weekly_average'],
                        'overall_average' => $consistencyData['trade']['overall_average'],
                        'low'             => $consistencyData['trade']['low'],
                        'high'            => $consistencyData['trade']['high'],

                    ],
                    'lot'                     => [
                        'count'           => $consistencyData['lot']['avg_lot'],
                        'weekly_average'  => $consistencyData['lot']['weekly_average'],
                        'overall_average' => $consistencyData['lot']['overall_average'],
                        'low'             => $consistencyData['lot']['low'],
                        'high'            => $consistencyData['lot']['high'],

                    ],
                    'used_standard_deviation' => $rulesData['st_deviation'] ?? config('settings.consistency.standard_deviation')
                ];
            }

            return [
                'account_details' => [
                    'account_details_label' => 'Account Details',
                    'type'                  => $this->account->plan->title,
                    'login'                 => $this->account->login,
                    'password'              => $this->account->password,
                    'investor_password'     => $this->account->investor_password,
                    'mt4_server'            => $this->account->server->server,
                ],
                'stats'           => [
                    'stats_label'           => 'Stats',
                    'drawdown_label'        => 'Drawdown',
                    'trading_days'          => $activeTradingDay,
                    'drawdown'              => $balance->current_balance - $balance->current_equity,
                    'equity_label'          => 'Equity',
                    'equity'                => $balance->current_equity,
                    'balance'               => $balance->current_balance,
                    'profit_label'          => 'Profit',
                    'profit'                => $balance->current_balance - $this->account->starting_balance,
                    'profit_target_label'   => 'Profit/Loss',
                    'profit_target'         => $profitTarget->profit_target,
                    'profit_target_reached' => $profitTarget->profit_target_reached,
                ],

                'trading_cycle_details' => [
                    'trading_cycle_details_label' => 'Trading Cycle Details',
                    'starting_date'               => $tradingCycle->starting_date,
                    'ending_date'                 => $tradingCycle->ending_date,
                ],

                'objectives' => [
                    'trading_days' => $tradingDays,

                    'daily_loss' => [
                        'label'                   => 'Max daily loss - $' . $this->calculateDailyMaxLossOf($this->account->starting_balance),
                        'label_static'            => $rulesData['dll_name'] ?? '',
                        'target_label'            => $rulesData['dll_target_label'] ?? '',
                        'target_value'            => '$' . number_format($allowedDailyLoss, 2),
                        'target_value_percentage' => $rulesData['dll_target_value'] ?? 0,
                        'current_label'           => 'Max. Loss recorded',
                        'current_value'           => '$' . number_format((float)isset($this->account->latestMetric) ? $this->account->latestMetric->maxDailyLoss : 0, 2),
                        'is_visible'              => $rulesData['is_dll_visible'] ?? false,
                        'status'                  => ($this->hasViolatedDailyMaxLossRule($allowedDailyLoss, ((float)isset($this->account->latestMetric) ? $this->account->latestMetric->maxDailyLoss : 0))) ? AppConstants::NOT_PASSED : AppConstants::ON_GOING
                    ],

                    'overall_loss' => [
                        'label' => 'Max loss - $' . $this->calculateMaxLossOf($this->account->starting_balance),

                        'label_static'            => $rulesData['mll_name'] ?? '',
                        'target_label'            => $rulesData['mll_target_label'] ?? '',
                        'target_value'            => '$' . number_format($allowedMonthlyLoss, 2),
                        'target_value_percentage' => $rulesData['mll_target_value'] ?? 0,
                        'current_label'           => 'Max. Loss recorded',
                        'current_value'           => '$' . number_format((float)$maxMonthlyLoss, 2),
                        'is_visible'              => $rulesData['is_mll_visible'] ?? false,
                        'status'                  => ($this->hasViolatedMaxLossRule($allowedMonthlyLoss, ((float)$maxMonthlyLoss))) ? AppConstants::NOT_PASSED : AppConstants::ON_GOING
                    ],

                    'profit' => $profit,
                ],

                'consistency'     => $consistency,
                'threshold'       => [
                    'threshold_label'      => 'Daily Loss & Max Loss',
                    'daily_loss_threshold' => '$' . round($lossThreeHold->daily_loss_threshold, 2),
                    'max_loss_threshold'   => '$' . round($lossThreeHold->max_loss_threshold, 2)
                ],
                'account_manager' => [
                    'label'   => 'Account Manager',
                    'manager' => $manager,
                ],

            ];
        } catch (Exception $exception) {
            Log::error("Account overview service error ", [$exception]);
            throw $exception;
        }
    }


    /**
     * Returns an array of account rules.
     *
     * @return array An array containing the account rules.
     */
    public function getAccountRules(): array
    {
        $rulesData = [];

        foreach ($this->planRules as $key => $rules) {
            switch ($key) {
                case 'MTD':
                    $rulesData['mtd_name']         = $rules['rule'];
                    $rulesData['mtd_target_label'] = 'Minimum';
                    $rulesData['mtd_target_value'] = $rules['value'];
                    $rulesData['is_mtd_visible']   = true;
                    break;
                case 'MLL':
                    $rulesData['mll_name']         = $rules['rule'];
                    $rulesData['mll_target_label'] = 'Max. Loss';
                    $rulesData['mll_target_value'] = (int)$rules['value'];
                    $rulesData['is_mll_visible']   = true;
                    break;
                case 'DLL':
                    $rulesData['dll_name']         = $rules['rule'];
                    $rulesData['dll_target_label'] = 'Max. Loss';
                    $rulesData['dll_target_value'] = (int)$rules['value'];
                    $rulesData['is_dll_visible']   = true;
                    break;
                case 'PT':
                    $rulesData['pt_name']         = $rules['rule'];
                    $rulesData['pt_target_label'] = 'Minimum';
                    $rulesData['pt_target_value'] = $rules['value'];
                    $rulesData['is_pt_visible']   = true;
                    break;
                case 'CRD':
                    $rulesData['st_deviation'] = $rules['value'];
                    break;
            }
        }

        return $rulesData;
    }


    /**
     * Calculates the trading cycle based on the latest subscription.
     *
     * @return object An object containing the starting and ending dates of the trading cycle.
     */
    public function tradingCycle(): object
    {
        $latestSubscription    = $this->latestSubscription();
        $datetimeFormat        = 'Y-m-d H:i:s';
        $data['starting_date'] = $latestSubscription->created_at;
        $date                  = new \DateTime();
        $date->setTimestamp(strtotime($latestSubscription->ending_at . "+1 day"));
        $data['ending_date'] = $date->format($datetimeFormat);
        $data['id']          = $latestSubscription->id;
        return (object)$data;
    }


    /**
     * Calculates the profit target and profit target reached based on the account and balance data.
     *
     * @param float $currentBalance current balance.
     * @return object An object containing the profit target and profit target reached.
     */
    protected function profitTarget(float $currentBalance): object
    {
        $data['profit_target']         = 0;
        $data['profit_target_reached'] = 0;

        if (isset($this->planRules['PT'])) {
            $data['profit_target'] = ($this->account->starting_balance) * ($this->planRules['PT']['value'] / 100);
            if (isset($this->planRules['AGF'])) {
                $growthFundAmount              = $this->account->growthFund->sum('amount');
                $data['profit_target_reached'] = (($currentBalance > ($this->account->starting_balance)) ? $currentBalance - ($this->account->starting_balance) : 0) + $growthFundAmount;
            } else {
                $data['profit_target_reached'] = ($currentBalance > ($this->account->starting_balance)) ? $currentBalance - ($this->account->starting_balance) : 0;
            }
        }
        return (object)$data;
    }


    /**
     * Returns the latest subscription associated with the account.
     *
     * @return Subscription The latest subscription associated with the account.
     */
    public function latestSubscription(): Subscription
    {
        return Subscription::where('account_id', $this->account->id)->latest('created_at')->first();
    }


    /**
     * Retrieves the current balance and equity data for the associated account.
     *
     * @return object An object containing the current balance and equity data.
     */
    public function getBalance(): object
    {
        $data      = [];
        $redisData = json_decode(Redis::get('margin:' . $this->account->login), 1);


        if ($redisData !== null) {
            $data['current_balance'] = $redisData['balance'];
            $data['current_equity']  = $redisData['equity'];
        } else {
            $server       = $this->account->server;
            $url          = $server->url;
            $sessionToken = $server->login;

            $redisDataFromApi = Http::get($url . "/user/margin/" . $this->account->login . "?token=" . $sessionToken);
            $redisData        = json_decode($redisDataFromApi, true);

            $data['current_balance'] = $redisData['balance'];
            $data['current_equity']  = $redisData['equity'];
        }
        return (object)$data;
    }


    /**
     * Checks if the current trading days have violated the minimum trading days rule.
     *
     * @param int $currentTradingDays The current number of trading days.
     * @param int $minimumTradingDays The minimum number of trading days required to satisfy the rule.
     * @return bool True if the current trading days have violated the rule, false otherwise.
     */
    public function hasViolatedTradingDaysRule(int $currentTradingDays, int $minimumTradingDays): bool
    {
        return $currentTradingDays < $minimumTradingDays;
    }


    /**
     * Calculates the daily maximum loss based on the provided balance and the percentage defined in the application settings.
     *
     * @param float $balance The balance to use in the calculation.
     * @return float The daily maximum loss based on the balance and the percentage defined in the application settings.
     */
    public function calculateDailyMaxLossOf(float $balance): float
    {
        return ($balance * config('settings.trading.daily_max_loss_percentage')) / 100;
    }


    /**
     * Calculates the daily maximum loss based on the provided balance and percentage.
     *
     * @param float $balance The balance to use in the calculation.
     * @param int $percentage The percentage to use in the calculation.
     * @return float The daily maximum loss based on the balance and percentage.
     */
    public function calculateDailyMaxLossByRuleOf(float $balance, int $percentage): float
    {
        return ($balance * $percentage) / 100;
    }


    /**
     * Checks if the actual daily loss has violated the allowed daily maximum loss rule.
     *
     * @param float $allowedLoss The maximum loss allowed for a day.
     * @param float $actuallyLoss The actual loss incurred for a day.
     * @return bool True if the actual daily loss has violated the allowed daily maximum loss rule, false otherwise.
     */
    public function hasViolatedDailyMaxLossRule(float $allowedLoss, float $actuallyLoss): bool
    {
        return abs($actuallyLoss) > $allowedLoss;
    }


    /**
     * Calculates the maximum loss based on the provided balance and the percentage defined in the application settings.
     *
     * @param float $balance The balance to use in the calculation.
     * @return float The maximum loss based on the balance and the percentage defined in the application settings.
     */
    public function calculateMaxLossOf(float $balance): float
    {
        return ($balance * config('settings.trading.max_loss_percentage')) / 100;
    }


    /**
     * Checks if the actual loss has violated the allowed maximum loss rule.
     *
     * @param float $allowedLoss The maximum loss allowed for the trading period.
     * @param float $actuallyLoss The actual loss incurred for the trading period.
     * @return bool True if the actual loss has violated the allowed maximum loss rule, false otherwise.
     */
    public function hasViolatedMaxLossRule(float $allowedLoss, float $actuallyLoss): bool
    {
        return abs($actuallyLoss) > $allowedLoss;
    }


    /**
     * Checks if the actual profit has hit the target profit rule.
     *
     * @param float $target The target profit to hit.
     * @param float $actually The actual profit made.
     * @return bool True if the actual profit has hit the target profit rule, false otherwise.
     */
    public function hasHitProfitTargetRule(float $target, float $actually): bool
    {
        return abs($actually) >= $target;
    }


    /**
     * @param float $currentEquity
     * @return object
     */
    public function lossThreeHold(float $currentEquity): object
    {
        $planRules = $this->planRules;
        if (isset($planRules['DLL'])) {
            $lastDayBalance     = isset($this->account->beforeLatestMetric) ? $this->account->beforeLatestMetric->lastBalance : 0;
            $dailyLossThreshold = ($planRules['DLL']['value'] / 100 * $this->account->starting_balance) - ($lastDayBalance - $currentEquity);
        } else {

            $dailyLossThreshold = null;
        }

        if (isset($planRules['MLL'])) {
            $maxLossThreshold = $currentEquity - ((1 - ($planRules['MLL']['value']) / 100) * $this->account->starting_balance);
        } else {
            $maxLossThreshold = null;
        }
        return (object)['max_loss_threshold' => $maxLossThreshold, 'daily_loss_threshold' => $dailyLossThreshold];
    }

    public function managerInfo()
    {

        $data = '{
          "id": 2,
          "name": "Rayyan Tariq",
          "avatar": "03_2022_media/CrBkkINGQKV12v4GvtqkyFBMFKDWU07IqpHct3Xy.png",
          "contacts": [
            {
              "type": "telegram",
              "label": "telegram",
              "value": "t.me/rayyan_fundednext"
            },
            {
              "type": "email",
              "label": "email",
              "value": "support@fundednext.com"
            }
          ],
          "status": 1,
          "created_at": "2022-03-01T10:08:39.000000Z",
          "updated_at": "2022-03-03T03:31:50.000000Z",
          "manager_image": "https://backend-evalution.fundednext.com/storage/03_2022_media%2FCrBkkINGQKV12v4GvtqkyFBMFKDWU07IqpHct3Xy.png"
         }';
        return json_decode($data);
    }

    public function getActiveTradingDaysByConsistencyCycleId(int $consistencyCycleId): Collection
    {
        return DailyTrading::where('consistency_cycle_id', $consistencyCycleId)
            ->where('trade_count', '>', 0)
            ->get();
    }

}
