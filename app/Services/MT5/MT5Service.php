<?php
namespace App\Services\MT5;


use App\Constants\AppConstants;
use App\Models\Account;
use App\Models\MtServer;
use Illuminate\Http\Response;


class MT5Service
{
    public function accountInfo(){
        return Account::where('breached', 0)->where('server_id', MtServer::MT5)->pluck('login');
    }
}
