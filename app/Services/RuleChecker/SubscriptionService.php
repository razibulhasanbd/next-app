<?php

namespace App\Services\RuleChecker;

use App\Models\Account;
use Carbon\Carbon;

class SubscriptionService
{
    public static function check(Account $account): bool
    {
        $endingDate = Carbon::parse($account->currentSubscription->ending_at);
        $now        = Carbon::now();

        return ($now->gte($endingDate)) ? true : false;
    }
}
