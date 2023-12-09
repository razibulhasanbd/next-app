<?php

namespace App\Repository;

use App\Models\Account;

class DownloadManagerRepository
{
    public function accountDataByPlanType($module)
    {
        $query = Account::select('accounts.*','customers.country_id','countries.name as country_name')
            ->with('latestSubscription','parentAccount')
            ->leftJoin('customers','customers.id' ,'=','accounts.customer_id')
            ->leftJoin('countries','countries.id' ,'=','customers.country_id');

        if($module == 'evaluation_real'){
            $query->where('type', 'like','%evaluation real%');
        }
        if($module == 'express_real'){
            $query->where('type', 'like','%express real%');
        }
        if($module == 'express_demo'){
            $query->where('type', 'like','%express demo%');
        }

        return $query->get();
    }


}
