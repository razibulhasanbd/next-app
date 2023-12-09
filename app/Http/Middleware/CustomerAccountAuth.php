<?php

namespace App\Http\Middleware;

use App\Helper\CustomAuthHelper;
use App\Models\JlTradingAccount;
use App\Services\ResponseService;
use Closure;
use Exception;
use Hamcrest\Type\IsNumeric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class CustomerAccountAuth
{
    public $userId;
    public $accountId;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->requestAuthorized($request)) {
            CustomAuthHelper::setCustomer($this->userId);
            CustomAuthHelper::setAccount($request->header('account-id-key'));
            return $next($request);
        }
        return ResponseService::apiResponse(401, "Unauthorized User");
    }


    /**
     * check the key and token
     *
     * @param mixed $request
     * @return bool
     */
    private function requestAuthorized($request) : bool{
        if ($request->header('account-id-key') && $request->bearerToken() && is_numeric($request->header('account-id-key'))){
            $this->userId = (Redis::connection('token'))->get($request->bearerToken());
            if (!$this->userId)
                return false;

            // $account = JlTradingAccount::where(['user_id' => $this->userId, 'broker_number' => $request->header('account-id-key')])->first();
            // if (!$account)
            //     return false;

            // $this->accountId = $account->broker_number;
            return true;
        }
        return false;
    }
}
