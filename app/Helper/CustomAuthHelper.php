<?php

namespace App\Helper;

use App\Models\Account;
use App\Models\JlUser;
use Illuminate\Support\Facades\Config;

class CustomAuthHelper
{
    /**
     * set customer id
     *
     * @param int $id
     * @return void
     */
    public static function setCustomer(int $id){
        Config::set('custom-auth.customer', $id);
    }


    /**
     * get customer data from JoulesLabs
     *
     * @return mixed
     */
    public static function getCustomer(){
        return JlUser::find(config('custom-auth.customer'));
    }


    /**
     * set account id
     *
     * @param int $id
     * @return void
     */
    public static function setAccount(int $id){
        Config::set('custom-auth.account', $id);
    }


    /**
     * get account
     *
     * @return Mixed
     */
    public static function getAccount(){
        return Account::find(config('custom-auth.account'));
    }
}
