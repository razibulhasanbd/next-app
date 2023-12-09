<?php

namespace App\Services\RuleChecker;

use App\Models\Account;
use App\Services\AccountService;
use App\Services\PlanRulesService;

class PTservice
{
    /**
     * checks profit target rule
     *
     * @param Account $account
     * @param mixed $rules
     * @param float|null $profitPercentage
     * @return boolean
     */
    public static function check(Account $account, mixed $rules = null, float $profitPercentage = null) : bool
    {
        $rules            = $rules ? $rules : (new PlanRulesService())->getRules($account);
        $profitPercentage = $profitPercentage ? $profitPercentage : (new AccountService())->getProfitPercentage($account);

        return ($profitPercentage >= $rules['PT']['value']) ? true : false;
    }
}
