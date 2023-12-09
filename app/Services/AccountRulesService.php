<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Account;
use App\Models\AccountRule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AccountRulesService
{


    public const SCALE_UP_BALANCE_RULE = 5;

    /**
     * @param Account $account
     *
     *
     * @return void
     */
    public function __construct(public Account $account)
    {
    }

    /**
     * @param Account $account
     *
     *
     * @return void
     */
    public function getRules()
    {


        $plan = Plan::with('breachRules')->where('id', $this->account->plan_id)->first();

        $rulesForPlans = [];

        $planRules = $plan['breachRules'];
        foreach ($planRules as $rule) {

            $result['rule'] = $rule->name;
            $result['condition'] = $rule->condition;
            $result['value'] = $rule->pivot->value;
            $result['is_percent'] = $rule->is_percent;
            $getArray[$plan->id][$rule->condition] = $result;
        }

        $planRules = $getArray;
        $result = null;

        $this->planRules =  $planRules;

        $allAccountsRules = AccountRule::where('account_id', $this->account->id)->get();
        $refactoredAccountRules = [];
        foreach ($allAccountsRules as $accountRule) {

            $result['rule'] = $accountRule->rule->name;
            $result['condition'] = $accountRule->rule->condition;
            $result['value'] = $accountRule->value;
            $result['is_percent'] = $accountRule->rule->is_percent;
            $result['is_accountRule'] = true;
            $refactoredAccountRules[$accountRule->account_id][$accountRule->rule->condition] = $result;
        }
        $this->allAccountRules = $refactoredAccountRules;
        $accountPlanRule = collect($this->planRules[$this->account->plan_id]);

        if ((isset($this->allAccountRules[$this->account->id]))) {

            $accountRules = collect($this->allAccountRules[$this->account->id]);
            return $accountRules->union($accountPlanRule);
        } else return $accountPlanRule;
    }


    /**
     * check if account has scale up rule
     *
     * @return ResponseService::basicResponse()
     */
    public function getScaleUpRule()
    {
        $scaleUpRule = AccountRule::where('account_id', $this->account->id)->where('rule_id', self::SCALE_UP_BALANCE_RULE)->first();

        if ($scaleUpRule) {
            $data = [
                'has_scaleup_rule' => true,
                'value' => $scaleUpRule->value,
            ];
            return ResponseService::basicResponse(200, "Scale up rule found", null, $data);
        } else {
            $data = [
                'has_scaleup_rule' => false,
                'value' => null,
            ];
            return ResponseService::basicResponse(200, "Scale up rule not found", null, $data);
        }
    }



    /**
     * set scale up rule for account
     *
     * @return void
     */
    public function setScaleUpRule(float $startingScaleUpBalance)
    {

        AccountRule::updateOrCreate(
            [
                'account_id' => $this->account->id,
                'rule_id' => self::SCALE_UP_BALANCE_RULE,
            ],
            [
                'value' => $startingScaleUpBalance,
            ]
        );

        $this->account->starting_balance= $startingScaleUpBalance;
        $this->account->save();
        Cache::forget($this->account->cacheKey() . ':account_rules');
    }


    /**
     * nca rule create for account
     *
     * @return void
     */
    public function ncaAccountRuleCreate() : void
    {
        AccountRule::insert(
            [
                [
                    "account_id" => $this->account->id,
                    "rule_id"    => 15,
                    "value"      => 1,
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ],
                [
                    "account_id" => $this->account->id,
                    "rule_id"    => 8,
                    "value"      => 1,
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ]
            ]
        );
    }


    /**
     * allow news trade rule create for account
     *
     * @return void
     */
    public function allowNewsTradeRuleCreate() : void
    {
        AccountRule::insert(
            [
                [
                    "account_id" => $this->account->id,
                    "rule_id"    => 16,
                    "value"      => 1,
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ]
            ]
        );
    }
}
