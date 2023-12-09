<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\Checkout\CardService;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CustomerCardController extends Controller
{
    public function cardInfo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
            if ($validator->fails()) {
                return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
            }
            return CardService::cardInfo($request->email);

        } catch (Exception $exception) {
            Log::error($exception);
            return ResponseService::apiResponse(500, "Internal server error");
        }
    }


}
