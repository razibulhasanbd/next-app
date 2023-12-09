<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\Plan;
use App\Models\Account;
use App\Services\TradeService;
use App\Helper\CustomAuthHelper;
use App\Services\AccountService;
use App\Services\ResponseService;
use App\Services\PlanRulesService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\AccountEligibility\AccountEligibilityService;
use App\Services\RuleChecker\PTservice;
use App\Services\RuleChecker\MTDservice;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ProfitChecker\Operations\EvaluationPhaseOne;
use Illuminate\Http\Request;

class AccountEligibilityApiController extends Controller
{

    /**
     * account status for all eligibility
     *
     * @return \Illuminate\Http\Response
     */
    public function status(){
        try {
            $account = CustomAuthHelper::getAccount();
//             $account = Account::find(2408);
//             $account = Account::find(2408);

            $response = (new AccountEligibilityService($account))->status();
            return ResponseService::apiResponse(200, '', $response);
        } catch (Exception $exception) {
            Log::error("AccountEligibilityApiController::accountPhaseOneEligibility", [$exception]);
            return ResponseService::apiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, "Internal Server Error");
        }

    }


    /**
     * account status for all eligibility
     *
     * @return \Illuminate\Http\Response
     */
    public function action(Request $request)
    {
        try {
            $account = CustomAuthHelper::getAccount();
            // $account = Account::find(2220);

            $response = (new AccountEligibilityService($account))->action($request->type);
            return ResponseService::apiResponse(200, '', $response);
        } catch (Exception $exception) {
            Log::error("AccountEligibilityApiController::accountPhaseOneEligibility", [$exception]);
            return ResponseService::apiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, "Internal Server Error");
        }
    }
}
