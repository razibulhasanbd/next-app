<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Services\AccountOverviewService;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function getAccountInfo(Request $request)
    {
        try {
            $this->validate($request, [
                'account_id' => 'required|integer'
            ]);
            $account = getAuthenticateAccount($request->account_id);
//            $account        = Account::with('plan', 'server')->find($request->account_id);
            if(!$account){
                return ResponseService::apiResponse(400, 'the account not found');
            }
            $account->load("plan","server");
            $accountService = new AccountOverviewService($account);
            $response       = $accountService->accountInfo();
            return ResponseService::apiResponse(200, '', $response);
        } catch (Exception $exception) {
            Log::error("Account create error: ", [$exception]);
            return ResponseService::apiResponse(500, 'Internal server error');
        }
    }
}

