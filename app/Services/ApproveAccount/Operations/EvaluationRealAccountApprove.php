<?php

namespace App\Services\ApproveAccount\Operations;

use Carbon\Carbon;
use App\Helper\Helper;
use App\Models\Account;
use App\Models\Customer;
use App\Models\AccountRule;
use App\Models\AccountStatus;
use App\Constants\AppConstants;
use App\Services\AccountService;
use Illuminate\Support\Facades\Log;
use App\Models\AccountStatusMessage;
use App\Models\TargetReachedAccount;
use App\Services\AccountRulesService;
use App\Services\AccountStatusLogService;

class EvaluationRealAccountApprove
{

    public static function approve(Account $account, TargetReachedAccount $targetReachedRow, array $profitAmounts, array $scaleUp = [])
    {
        $accountService = new AccountService();

        $accountService->clientUpdateProfit($account, $profitAmounts);
        if ($scaleUp['willScaleUp']) {
            $setScaleUp = (new AccountRulesService($account))->setScaleUpRule($scaleUp['scaleUpAmount']);
            Helper::discordAlert("**Scale Up**\nAccount:" . $account->id . "\nAmount: " . $scaleUp['scaleUpAmount']);
            if($account->customer->tags != 0 || $account->customer->tags != null){
                Helper::discordAlert("
                **" . Customer::TAGS[$account->customer->tags] . "Customer" . "**:
                **Scale Up**\nAccount:" . $account->id . "\nAmount: " . $scaleUp['scaleUpAmount']
                ,true);
            }
            AccountStatusLogService::create($account, AccountStatus::SCALED_UP, null, $scaleUp['scaleUpAmount']);
            $account->refresh();
        }
        $margin = $accountService->margin($account);
        $accountService->accountBalanceReset($account, $margin);
        $accountService->newSubscriptionCreate($account, AppConstants::EV_REAL_FIRST_CYCLE_15_DAYS);
        $accountService->enableAccount($account);
        $account->duration = AppConstants::EV_REAL_FIRST_CYCLE_15_DAYS;
        $account->save();
        AccountStatusLogService::create($account, AccountStatus::RESET, AccountStatusMessage::APPROVED_FROM_TRA, []);

        $targetReachedRow->update(['approved_at' => Carbon::now()]);
        Helper::discordAlert("**Approval Confirmation**:\nFor: " . $targetReachedRow->approval_category->name . "\nLogin : " . $account->login . "\nPlan : " . $account->plan->title . "\nProfit : " . $profitAmounts['profit'] . "\nwithdrawableAmount : " .  $profitAmounts['withdrawableAmount'] . "\ngrowthFund : " . $profitAmounts['growthFundAmount']);
        if($account->customer->tags != 0 || $account->customer->tags != null){
            Helper::discordAlert("
            **" . Customer::TAGS[$account->customer->tags] . "Customer" . "**:
            **Approval Confirmation**:\nFor: " . $targetReachedRow->approval_category->name . "\nLogin : " . $account->login . "\nPlan : " . $account->plan->title . "\nProfit : " . $profitAmounts['profit'] . "\nwithdrawableAmount : " .  $profitAmounts['withdrawableAmount'] . "\ngrowthFund : " . $profitAmounts['growthFundAmount']
            ,true);
        }
    }
}
