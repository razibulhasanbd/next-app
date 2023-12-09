<?php

namespace App\Services\RuleChecker;

use App\Models\Account;
use App\Services\PlanRulesService;

class MTDservice
{
    /**
     * checks minimum trading days rule
     *
     * @param Account $account
     * @param mixed $rules
     * @return boolean
     */
    public static function check(Account $account, mixed $rules = null) : bool{
        $rules = $rules ? $rules : (new PlanRulesService(true))->getRules($account);

        return ($account->tradingDays() >= $rules['MTD']['value']) ? true : false;
    }
}
