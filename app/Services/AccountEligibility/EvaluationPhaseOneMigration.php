<?php

namespace App\Services\AccountEligibility;

use Throwable;
use App\Models\Plan;
use App\Helper\Helper;
use App\Models\Account;
use App\Models\Customer;
use App\Models\AccountStatus;
use App\Services\TradeService;
use App\Services\AccountService;
use App\Services\ResponseService;
use App\Services\PlanRulesService;
use App\Services\RuleChecker\PTservice;
use App\Services\RuleChecker\MTDservice;
use App\Services\AccountStatusLogService;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ProfitChecker\Operations\EvaluationPhaseOne;

class EvaluationPhaseOneMigration
{
    /**
     * account p1 p2 eligibility check
     *
     * @param Account $account
     * @return array
     * @throws $exception
     */
    public function phaseOneMigrationEligibilityCheck(Account $account) : array{
        try {
            if ($account->breached) {
                return ResponseService::basicResponse(Response::HTTP_PARTIAL_CONTENT, "Not eligible for phase two account. Breached account.", [], false);
            }

            if ($account->plan->type != Plan::EV_P1) {
                return ResponseService::basicResponse(Response::HTTP_PARTIAL_CONTENT, "Not eligible for phase two account. Not evaluation phase one account.", [], false);
            }

            $runningTrades = (new TradeService())->getRunningTrades($account);
            if (sizeof($runningTrades)) {
                return ResponseService::basicResponse(Response::HTTP_PARTIAL_CONTENT, "Not eligible for phase two account. Account has running trades.", [], false);
            }

            $planRuleService = new PlanRulesService(true);
            $accountService  = new AccountService();

            $rules  = $planRuleService->getRules($account);
            $margin = $accountService->margin($account);

            $startingBalance  = $account->plan->startingBalance;
            $accountProfit    = $margin["currentBalance"] - $startingBalance;
            $profitPercentage = ($accountProfit / $startingBalance) * 100;

            $mtdStatus          = MTDservice::check($account, $rules);
            $profitTargetStatus = PTservice::check($account, $rules, $profitPercentage);

            if ($mtdStatus && $profitTargetStatus) {
                return ResponseService::basicResponse(Response::HTTP_OK, "Eligible for phase two account", [], true);
            } else {
                return ResponseService::basicResponse(Response::HTTP_PARTIAL_CONTENT, "Not eligible for phase two account. Because target is not fulfilled yet.", [], false);
            }
        } catch (Throwable $exception) {
            throw $exception;
        }
    }


    /**
     * p1 p2 request
     *
     * @param Account $account
     * @return array
     */
    public function phaseOneToPhaseTwoMigrateRequest(Account $account) : array
    {
        try {
            AccountStatusLogService::create($account, AccountStatus::EVALUATION_PHASE_1_2_MIGRATION_REQUEST);
            $response = EvaluationPhaseOne::checker($account);
            if ($response['code'] == EvaluationPhaseOne::ACCOUNT_P2_MIGRATED) {
                Helper::discordAlert("**EV P1 to P2 By User**:\nAccntID : " . $account->id . "\nLogin : " . $account->login);
                if($account->customer->tags != 0 || $account->customer->tags != null){
                    Helper::discordAlert("
                    **" . Customer::TAGS[$account->customer->tags] . "Customer" . "**:
                    **EV P1 to P2 By User**:\nAccntID : " . $account->id . "\nLogin : " . $account->login
                    ,true);
                }
                return ResponseService::basicResponse(Response::HTTP_OK, "Account migrated successfully. Please check after sometime.", [], true);
            } else {
                return ResponseService::basicResponse(Response::HTTP_PARTIAL_CONTENT, $response['message'], [],  false);
            }
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

}
