<?php

namespace App\Services\RulesService;

use App\Models\Account;
use App\Models\Plan;

class DurationService
{
    /**
     * get the cycle duration for an account in days
     *
     * @param Account $account
     * @return integer
     */
    public function getDuration(Account $account, $toCreateNew = false)
    {

        $duration = 0;

        switch ($account->plan->type) {
            case Plan::EV_REAL:
        
                if ((count($account->subscriptions) > 1) || ($toCreateNew)) {
                    $duration = 15;
                } else {
                    $duration = $account->plan->duration;
                }
                break;
            default:
                $duration = $account->plan->duration;
                break;
        }

        return $duration;
    }
}
