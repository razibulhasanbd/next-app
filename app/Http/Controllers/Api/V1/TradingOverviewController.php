<?php

namespace App\Http\Controllers\Api\V1;

use App\Constants\AppConstants;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountMetric;
use App\Services\AccountOverviewService;
use App\Services\ResponseService;
use App\Services\TradingOverviewService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TradingOverviewController extends Controller
{
    public function getTradingInfo(Request $request)
    {
        try {
            $this->validate($request, [
                'account_id' => 'required|integer',
                'type' => 'required',
            ]);
            $account = getAuthenticateAccount($request->account_id);
            if(!$account){
                return ResponseService::apiResponse(400, 'the account not found');
            }
            $account->load("plan","server");

            $tradingService = new TradingOverviewService($account);

            if ($request->type == AppConstants::TRADING_STATS) {
                $response = $tradingService->detailsStatus();
            } elseif ($request->type == AppConstants::TRADING_ANALYTICS) {
                $response = $tradingService->accountGrowthStatus($request);
            } elseif ($request->type == AppConstants::TRADING_SYMBOL) {
                $response = $tradingService->symbolPerformance();
            } elseif ($request->type == AppConstants::TRADING_SYMBOL) {
                $response = $tradingService->symbolPerformance();
            } elseif ($request->type == AppConstants::WEEKLY_PROFIT_LOSS) {
                $response = $tradingService->weeklyProfitLoss();
            } elseif ($request->type == AppConstants::AVERAGE_PROFIT_LOSS_PERCENTAGE) {
                $response = $tradingService->averageProfitLoss();
            } elseif ($request->type == AppConstants::BY_SEL_ORDER_TYPE) {
                $response = $tradingService->buySellOrderType();
            } elseif ($request->type == AppConstants::HOURLY_PROFIT_LOSS) {
                $response = $tradingService->hourlyProfitLoss();
            }else{
                return ResponseService::apiResponse(400, 'perimeter is missing');
            }

            return ResponseService::apiResponse(200, '', $response);
        } catch (Exception $exception) {
            Log::error("Trading graph: ", [$exception]);
            return ResponseService::apiResponse(500, 'Internal server error');
        }
    }

}

