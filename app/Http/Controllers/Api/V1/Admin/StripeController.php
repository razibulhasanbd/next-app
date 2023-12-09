<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Constants\AppConstants;
use App\Http\Controllers\Controller;
use App\Models\JlPlan;
use App\Services\Checkout\CheckoutService;
use App\Services\ResponseService;
use App\Services\Stripe\StripeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StripeController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type'                            => 'required',
                'plan_id'                       => 'nullable|exists:' . AppConstants::DATABASE_CONNECTION_FUNDED_NEXT_BACKEND . '.plans,id',
                'first_name'=> 'required|max:50',
                'last_name'=> 'nullable',
                'email'=> 'required|email|max:255',
            ]);
            if ($validator->fails()) {
                return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
            }

            $customer = getAuthCustomer();
            if (!$customer) {
                return ResponseService::apiResponse(400, 'customer not match');
            }
            $name = $customer->name;
            if($request->type == 1){ // card add
                $amount = 1; // request for payment intent
            }else{
                $plan = JlPlan::find($request->plan_id);
                $amount = $plan->price;
            }

            $response = (new StripeService)->requestPaymentIntent($amount, $name, $request->email);
            if ($response->successful()) {
                $responseDecode = json_decode($response->body());
                return ResponseService::apiResponse(200, 'success', $responseDecode->data);
            }
            Log::error("stripe payment intent request failed ", [$response]);
            return ResponseService::apiResponse(400, 'Payment request is not successful. Please kindly knock in live chat.');

        } catch (Exception $exception) {
            dd($exception->getMessage());
            Log::error("payment intent exception", [$exception]);
            return ResponseService::apiResponse(500, "Internal server error");
        }

    }


}
