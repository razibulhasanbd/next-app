<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Account;
use App\Models\PlanRule;
use App\Models\AccountRule;

class PlanRulesService
{


    public $planRules, $accountRules, $withAccountRules;
    public function __construct(bool $withAccountRules = false)
    {

        $this->withAccountRules = $withAccountRules;

        $plans = Plan::with('breachRules')->get();


        $rulesForPlans = [];

        foreach ($plans as $plan) {

            $planRules = $plan['breachRules'];
            foreach ($planRules as $rule) {

                $result['rule'] = $rule->name;
                $result['condition'] = $rule->condition;
                $result['value'] = $rule->pivot->value;
                $result['is_percent'] = $rule->is_percent;
                $getArray[$plan->id][$rule->condition] = $result;
            }
        }
        $planRules = $getArray;
        $result = null;

        $this->planRules =  $planRules;

        if ($this->withAccountRules) {

            $allAccountsRules = AccountRule::all();
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
        } else {
            $this->allAccountRules = [];
        }
    }


    public function getRules($account)
    {

        $accountPlanRule = collect($this->planRules[$account->plan_id]);

        if (($this->withAccountRules) && (isset($this->allAccountRules[$account->id]))) {

            $accountRules = collect($this->allAccountRules[$account->id]);
            return $accountRules->union($accountPlanRule);
        } else return $accountPlanRule;
    }
}
