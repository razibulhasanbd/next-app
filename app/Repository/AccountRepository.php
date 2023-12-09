<?php

namespace App\Repository;

use App\Models\Account;

class AccountRepository
{

    public function notBreachedAccounts()
    {
        return Account::with(['plan', 'plan.server', 'latestTwoMetrics'])->where('breached', '0')->get();
    }
}
