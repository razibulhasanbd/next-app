<?php

namespace App\Services\ProfitChecker\Operations;

use Carbon\Carbon;
use App\Models\Plan;
use App\Helper\Helper;
use App\Jobs\EmailJob;
use App\Models\Account;
use App\Models\MtServer;
use App\Jobs\BreachEventJob;
use App\Models\AccountStatus;
use App\Services\TradeService;
use App\Constants\AppConstants;
use App\Models\ApprovalCategory;
use App\Services\AccountService;
use App\Constants\EmailConstants;
use App\Services\PlanRulesService;
use Illuminate\Support\Facades\Log;
use App\Models\AccountStatusMessage;
use App\Models\TargetReachedAccount;
use App\Services\RuleChecker\PTservice;
use App\Services\RuleChecker\MTDservice;
use App\Services\AccountStatusLogService;
use App\Services\RuleChecker\SubscriptionService;

class EvaluationPhaseOne
{

    public const ACCOUNT_RUNNING_TRADES = 1;
    public const ACCOUNT_RESET          = 2;
    public const ACCOUNT_P2_MIGRATED    = 3;
    public const ACCOUNT_PAUSE          = 4;
    public const ACCOUNT_NOT_ELIGIBLE   = 5;

    /**
     * Evaluation phase one checker
     *
     * @param Account $account
     * @return array
     */
    public static function checker(Account $account): array
    {

        $planRuleService = new PlanRulesService(true);
        $accountService  = new AccountService();

        $rules  = $planRuleService->getRules($account);
        $margin = $accountService->margin($account);

        $startingBalance  = $account->starting_balance;
        $accountProfit    = $margin["currentBalance"] - $startingBalance;
        $profitPercentage = ($accountProfit / $startingBalance) * 100;

        $mtdStatus          = MTDservice::check($account, $rules);
        $profitTargetStatus = PTservice::check($account, $rules, $profitPercentage);
        $subscriptionStatus = SubscriptionService::check($account);
        $profitStatus       = ($accountProfit > 0) ? true : false;

        if (!$subscriptionStatus) {
            $runningTrades = (new TradeService())->getRunningTrades($account);
            if (sizeof($runningTrades)) {
                return [
                    "status"  => false,
                    "message" => "Account has running trades",
                    "code"    => self::ACCOUNT_RUNNING_TRADES
                ];
            }
        }

        (new self)->unsetRelation($account);

        if ($subscriptionStatus && $profitStatus && $mtdStatus && !$profitTargetStatus) { // reset account
            (new self)->targetReachAccountCreate($account, Account::MONTHEND_PARTIAL_PROFIT_SHARE_APPROVAL, $margin, $startingBalance, $profitPercentage, null, Carbon::now());
            $accountService->accountReset($account, $margin);
            AccountStatusLogService::create($account, AccountStatus::RESET, AccountStatusMessage::MONTH_END_PARTIAL_PROFIT_MTD_FULFILLED);

            if ($account->plan->type == Plan::EV_P1) {

                $details = [
                    'template_id'          => EmailConstants::RESET_FREE_RETAKE,
                    'to_name'              => Helper::getOnlyCustomerName($account->customer->name),
                    'to_email'             => $account->customer->email,
                    'email_body' => [
                        "name" => Helper::getOnlyCustomerName($account->customer->name),
                        "login_id" => $account->login,
                    ]
                ];
                EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
            }
            return [
                "status"  => true,
                "message" => "Account Reset",
                "code"    => self::ACCOUNT_RESET
            ];
        } elseif ($mtdStatus && $profitTargetStatus) { // p2 migrate
            //Check Duplicate
            BreachEventJob::dispatch($account, ApprovalCategory::find(1)->name, $margin)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
            $accountService->phaseMigration($account);
            (new self)->targetReachAccountCreate($account, Account::PROFIT_TARGET_REACHED_APPROVAL, $margin, $startingBalance, $profitPercentage, Carbon::now(), null);
            AccountStatusLogService::create($account, AccountStatus::MIGRATED, AccountStatusMessage::PROFIT_TARGET_REACHED);
            return [
                "status"  => true,
                "message" => "The account has been migrated to P2.",
                "code"    => self::ACCOUNT_P2_MIGRATED
            ];
        } elseif ($subscriptionStatus) { // account cancel/pause
            BreachEventJob::dispatch($account, "Month Ended in Loss", $margin)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
            AccountStatusLogService::create($account, AccountStatus::CANCELED, (new self)->messageDetectForPauseAccount($profitStatus, $mtdStatus));
            return [
                "status"  => true,
                "message" => "Account Paused.",
                "code"    => self::ACCOUNT_PAUSE
            ];
        }
        return [
            "status"  => false,
            "message" => "Account is not eligible for this stage.",
            "code"    => self::ACCOUNT_NOT_ELIGIBLE
        ];
    }


    /**
     * target reached account create for in denied status
     *
     * @param Account $account
     * @param Integer $approvalCategoryId
     * @param array $margin
     * @param float $startingBalance
     * @param float $profitPercentage
     * @param Carbon|null $approvedAt
     * @param Carbon|null $deniedAt
     * @return void
     */
    private function targetReachAccountCreate(Account $account, int $approvalCategoryId, array $margin, float $startingBalance, float $profitPercentage, Carbon $approvedAt = null, Carbon $deniedAt = null): void
    {
        $tradingDays = (new AccountService)->tradingDays($account);
        TargetReachedAccount::create(
            [
                'account_id'           => $account->id,
                'approval_category_id' => $approvalCategoryId,
                'metric_info'          => json_encode([
                    'balance'          => $margin["currentBalance"],
                    'equity'           => $margin['currentEquity'],
                    'starting_balance' => $startingBalance,

                ]),
                'rules_reached' => json_encode([
                    'minimum_trading_days' => $tradingDays,
                    'consistency_rule'     => true,
                    'profit_target'        => $profitPercentage,
                    'news'                 => json_encode([]),
                ]),
                'plan_id'         => $account->plan_id,
                'subscription_id' => $account->subscription_id,
                'denied_at'       => $deniedAt,
                'approved_at'     => $approvedAt,
            ]
        );
    }


    /**
     * message detect for paused account
     *
     * @param boolean $profitStatus
     * @param boolean $mtdStatus
     * @return integer
     */
    private function messageDetectForPauseAccount(bool $profitStatus, bool $mtdStatus): int
    {
        if ($mtdStatus && !$profitStatus) {
            return AccountStatusMessage::MONTH_ENDED_IN_LOSS_MTD_FULFILLED;
        } elseif (!$mtdStatus && $profitStatus) {
            return AccountStatusMessage::MONTH_END_PARTIAL_PROFIT_MTD_NOT_FULFILLED;
        } else {
            return AccountStatusMessage::MONTH_END_LOSS_MTD_NOT_FULFILLED;
        }
    }


    /**
     * unset account relation
     *
     * @param Account $account
     * @return void
     */
    private function unsetRelation(Account $account): void
    {
        $account->unsetRelation('thisCycleMetrics');
        $account->unsetRelation('currentSubscription');
        $account->unsetRelation('latestSubscription');
        $account->unsetRelation('todayMetric');
        $account->unsetRelation('plan');
    }
}
