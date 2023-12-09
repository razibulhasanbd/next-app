<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\MT5\MT5Service;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MT5Controller extends Controller
{
    public function accountInfo(Request $request)
    {
        if (config('mt5.COMMUNICATION_KEY') == $request->header("communication-key")) {
            try {
                $mtServerInfo = (new MT5Service())->accountInfo();
                return ResponseService::apiResponse(200, "MT5 LogIn", $mtServerInfo);

            } catch (Exception $exception) {
                Log::error("MT5 exception", [$exception]);
                return ResponseService::apiResponse(500, "Internal server error");
            }
        }
        return ResponseService::apiResponse(401, "Unauthorized");

    }


}
