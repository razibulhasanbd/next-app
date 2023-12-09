<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Constants\AppConstants;
use App\Http\Controllers\Controller;
use App\Services\Checkout\CheckoutService;
use App\Services\ResponseService;
use App\Services\Stripe\StripeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function productOrder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type'                             => ['required', Rule::in([AppConstants::PRODUCT_ORDER_NEW_ACCOUNT, AppConstants::PRODUCT_ORDER_TOPUP, AppConstants::PRODUCT_ORDER_RESET])],
                'first_name'                       => 'required_if:type,' . AppConstants::PRODUCT_ORDER_NEW_ACCOUNT . "|max:50|regex:/^[a-zA-Z ]+$/",
                'last_name'                        => 'nullable|max:50|regex:/^[a-zA-Z ]+$/',
                'email'                            => 'required_if:type,' . AppConstants::PRODUCT_ORDER_NEW_ACCOUNT . '|email|max:100',
                'plan_id'                          => 'required_if:type,' . AppConstants::PRODUCT_ORDER_NEW_ACCOUNT . '|exists:' . AppConstants::DATABASE_CONNECTION_FUNDED_NEXT_BACKEND . '.plans,id',
                'login'                            => 'required_unless:type,' . AppConstants::PRODUCT_ORDER_NEW_ACCOUNT,
                'token'                            => 'nullable',
                'remarks'                          => 'nullable|string',
                'coupon_code'                      => 'nullable|string',
                'password'                         => 'nullable|string|max:100',
                'success_url'                      => 'nullable|string|max:220',
                'fail_url'                         => 'nullable|string|max:220',
                'gateway'                          => 'required|in:'.AppConstants::GATEWAY_CHECKOUT.','.AppConstants::GATEWAY_STRIPE,
                'billing_address'                  => "nullable|array",
                'billing_address.country'          => "nullable|integer|exists:countries,id",
                'billing_address.state'            => "nullable|string",
                'billing_address.zip_code'         => "nullable|string",
                'billing_address.phone'            => "nullable",
                'billing_address.card_holder_name' => "nullable|string",
                'server_name'                      => 'sometimes|nullable|in:' . AppConstants::TRADING_SERVER_MT4 . ',' . AppConstants::TRADING_SERVER_MT5,
            ]);
            if ($validator->fails()) {
                return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
            }

            return (new CheckoutService)->makePayment($request);
        } catch (Exception $exception) {
            Log::error("Product order exception",[$exception]);
            return ResponseService::apiResponse(500, "Internal server error");
        }
    }

    public function confirmOrder(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'cko_session_id' => 'required|string',
//                'password' => 'nullable|string',
                 'gateway'        => 'required|in:'.AppConstants::GATEWAY_CHECKOUT.','.AppConstants::GATEWAY_STRIPE,
                // 'first_name'     => 'required_if:gateway,' . AppConstants::GATEWAY_STRIPE. '|max:50',
                // 'email'          => 'required_if:gateway,' . AppConstants::GATEWAY_STRIPE,
                // 'plan_id'        => 'required_if:gateway,' . AppConstants::GATEWAY_STRIPE . '|exists:' . AppConstants::DATABASE_CONNECTION_FUNDED_NEXT_BACKEND . '.plans,id',
                // 'type'           => 'required_if:gateway,' . AppConstants::GATEWAY_STRIPE
            ]);

            if ($validator->fails()) {
                return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
            }

            return CheckoutService::confirmPayment($request);
        } catch (Exception $exception) {
            Log::error("Product order excception",[$exception]);
            return ResponseService::apiResponse(500, "Internal server error");
        }
    }


}
