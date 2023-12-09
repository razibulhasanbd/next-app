<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Constants\AppConstants;
use App\Http\Controllers\Controller;
use App\Models\JlPlan;
use App\Services\Checkout\CouponService;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function couponCheck(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'plan_id'     => 'required|integer|exists:' . AppConstants::DATABASE_CONNECTION_FUNDED_NEXT_BACKEND . '.plans,id',
                'coupon_code' => 'required|max:50'
            ]);

            if ($validator->fails()) {
                return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
            }

            if ($coupon = CouponService::couponValidateCheck($request->coupon_code)) {
                $plan = JlPlan::find($request->plan_id);
                $amountDetails = CouponService::couponPrice($coupon, $plan);
                return ResponseService::apiResponse(200, "Coupon applied successfully!", [
                    "final_amounts" => [
                        'old_amount'     => $amountDetails['old_amount'] / 100,
                        'coupon_amount'  => $amountDetails['coupon_amount'] / 100,
                        'payable_amount' => $amountDetails['payable_amount'] / 100,
                    ]
                ]);
            }
            return ResponseService::apiResponse(400, "The coupon code is not valid");

        } catch (Exception $exception) {
            Log::error("Coupon check exception", [$exception]);
            return ResponseService::apiResponse(500, "Internal server error");
        }
    }


}
