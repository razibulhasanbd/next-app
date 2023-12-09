<?php

namespace App\Services\Checkout;

use App\Constants\AppConstants;
use App\DataSource\OrderData;
use App\Jobs\AffiliateApiCallJob;
use App\Jobs\InvoiceGenerateJob;
use App\Models\Account;
use App\Models\Country;
use App\Models\JlPlan;
use App\Models\JlTradingAccount;
use App\Models\Orders;
use App\Services\OrderService;
use App\Services\ResponseService;
use App\Services\Stripe\StripeService;
use App\Services\TradingAccountService;
use App\Traits\Auditable;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckoutService
{
    public const type = [
        1 => 'new account',
        2 => 'topup',
        3 => 'reset'
    ];

    use Auditable;

    /**
     * Make payment call ph backend for stripe, checkout
     * @param $request
     * @return Response
     */
    public static function makePayment($request)
    //TODO we will make all order system in general
    {
        if ($request->type == AppConstants::PRODUCT_ORDER_NEW_ACCOUNT) {
            $plan = JlPlan::find($request->plan_id);
            $reference = $plan->name ."-"  . $request->type;
            $amount = $plan->price;
            if (isset($request->coupon_code)) {
                $coupon = CouponService::couponValidateCheck($request->coupon_code);
                if($coupon){
                    $couponDiscountPrice = CouponService::couponPrice($coupon, $plan);
                    $amount = $couponDiscountPrice['payable_amount'];
                    $reference = $reference . "-". $coupon->coupon_code;
                } else {
                    return ResponseService::apiResponse(400, 'The Coupon code is invalid');
                }
            }
            $email = $request->email;
            $name = $request->first_name . ' ' . $request->last_name;
        } else {
            $account = Account::with('customer')->whereLogin($request->login)->first();
            $jlTradingAccount = JlTradingAccount::where('broker_number', $account->id)->first();
            $plan = JlPlan::find($jlTradingAccount->plan_id);
            $reference = $plan->name . "-"  . $request->type;

            if ($request->type == AppConstants::PRODUCT_ORDER_TOPUP) {
                $amount = $plan->topup_charge;
            } elseif ($request->type == AppConstants::PRODUCT_ORDER_RESET) {
                $amount = $plan->reset_charge;
            }
            $email = $account->customer->email;
            $name = $account->customer->name;
        }

        $gatewayToken = (new CheckoutService)->getGatewayTokenFromGatewayId($request->gateway);

        if($request->gateway == AppConstants::GATEWAY_STRIPE){ // for stripe
            $response = (new StripeService)->requestPaymentIntent($amount / 100, $name, $email);
            if ($response->successful()) {
                $responseDecode = json_decode($response->body());

                $customer                   = (new TradingAccountService())->createCustomer(['email' => $email, 'name' => $name, 'country_id' => $request->country_id ?? null]);
                $orderData                  = new OrderData();
                $orderData->accountId       = isset($account) ? $account->id : null;
                $orderData->customerId      = $customer->id;
                $orderData->email           = $email;
                $orderData->orderType       = $request->type;
                $orderData->gateway         = AppConstants::GATEWAY_STRIPE;
                $orderData->status          = Orders::STATUS_PENDING;
                $orderData->transactionId   = $responseDecode->data->p_id;
                $orderData->jlPlanId        = $plan->id;
                $orderData->couponId        = isset($coupon) ? $coupon->id : null;
                $orderData->serverName      = $request->server_name ?? AppConstants::TRADING_SERVER_MT4;
                $orderData->billing_address = $request->billing_address ? json_encode($request->billing_address): null;
                if (isset($coupon) && isset($couponDiscountPrice)) {
                    $orderData->total         = $couponDiscountPrice['old_amount'] / 100;
                    $orderData->discount      = $couponDiscountPrice['coupon_amount'] / 100;
                    $orderData->gradTotal     = $couponDiscountPrice['payable_amount'] / 100;
                } else {
                    $orderData->total         = $amount / 100;
                    $orderData->discount      = 0;
                    $orderData->gradTotal     = $amount / 100;
                }
                (new OrderService())->generateOrder($orderData);
                return ResponseService::apiResponse(200, 'success', [
                    'client_secret' => $responseDecode->data->paymentIntent,
                ]);
            }
            Log::error("stripe payment intent request failed ", [$response]);
            return ResponseService::apiResponse(400, 'Payment request is not successful with stripe. Please kindly knock in live chat.');
        }

        // checkout
        if($request->billing_address){ // add county short name for checkout
            $billing_address = $request->billing_address;
            $country = Country::find($billing_address['country'] ?? null);
            $billing_address['country_short_name'] = $country ? $country->short_name: null;
            $request->billing_address = $billing_address;
        }

        $paymentResponse = PaymentHubService::requestPayment($name, $email, $request->token, $amount, $reference, $request->success_url, $request->fail_url, $gatewayToken, $request->billing_address);

        if ($paymentResponse->status() == 200) {
            $decodeResponse = json_decode($paymentResponse->body());
            $transactionId = $decodeResponse->data->response->data->response->id;

            if ($request->type == AppConstants::PRODUCT_ORDER_NEW_ACCOUNT) {
                $response = self::newAccount($request, $transactionId);
            } else {
                $response = self::topupReset($account, $request->type, $transactionId);
            }

            if ($response['code'] == 200) { // regular payment
                $orderData                  = new OrderData();
                $orderData->accountId       = isset($account) ? $account->id : null;
                $orderData->customerId      = isset($account->customer) ? $account->customer->id : null;
                $orderData->email           = $email;
                $orderData->orderType       = $request->type;
                $orderData->gateway         = ($request->gateway == AppConstants::GATEWAY_CHECKOUT) ? AppConstants::GATEWAY_CHECKOUT : AppConstants::GATEWAY_STRIPE;
                $orderData->transactionId   = $transactionId;
                $orderData->status          = Orders::STATUS_ENABLE;
                $orderData->jlPlanId        = $plan->id;
                $orderData->serverName      = $request->server_name ?? AppConstants::TRADING_SERVER_MT4;
                $orderData->couponId        = isset($coupon) ? $coupon->id : null;
                $orderData->billing_address = $request->billing_address ? json_encode($request->billing_address): null;
                if (isset($coupon) && isset($couponDiscountPrice)) {
                    $orderData->total         = $couponDiscountPrice['old_amount'] / 100;
                    $orderData->discount      = $couponDiscountPrice['coupon_amount'] / 100;
                    $orderData->gradTotal     = $couponDiscountPrice['payable_amount'] / 100;
                } else {
                    $orderData->total         = $amount / 100;
                    $orderData->discount      = 0;
                    $orderData->gradTotal     = $amount / 100;
                }

                (new OrderService())->generateOrder($orderData);

                if($orderData->orderType == AppConstants::PRODUCT_ORDER_NEW_ACCOUNT){
                    AffiliateApiCallJob::dispatch($orderData)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
                }
                return ResponseService::apiResponse(200, $response['message']);
            }
            return ResponseService::apiResponse(400, $response['message']);
        }
        elseif($paymentResponse->status() == 202){ // 3ds payment
            $decodeResponse = json_decode($paymentResponse->body());
            $redirectUrl = $decodeResponse->data->response->data->redirect_url;
            $customer = (new TradingAccountService())->createCustomer(['email' => $email, 'name' => $name, 'country_id' => $request->country_id ?? null]);

            $orderData                  = new OrderData();
            $orderData->accountId       = isset($account) ? $account->id : null;
            $orderData->customerId      = $customer->id;
            $orderData->email           = $email;
            $orderData->orderType       = $request->type;
            $orderData->gateway         = AppConstants::GATEWAY_CHECKOUT;
            $orderData->status          = Orders::STATUS_PENDING;
            $orderData->transactionId   = $decodeResponse->data->response->data->response->id;
            $orderData->jlPlanId        = $plan->id;
            $orderData->serverName      = $request->server_name ?? AppConstants::TRADING_SERVER_MT4;
            $orderData->couponId        = isset($coupon) ? $coupon->id : null;
            $orderData->billing_address = $request->billing_address ? json_encode($request->billing_address): null;
            if (isset($coupon) && isset($couponDiscountPrice)) {
                $orderData->total         = $couponDiscountPrice['old_amount'] / 100;
                $orderData->discount      = $couponDiscountPrice['coupon_amount'] / 100;
                $orderData->gradTotal     = $couponDiscountPrice['payable_amount'] / 100;
            } else {
                $orderData->total         = $amount / 100;
                $orderData->discount      = 0;
                $orderData->gradTotal     = $amount / 100;
            }

            (new OrderService())->generateOrder($orderData);

            return ResponseService::apiResponse(202, "Redirect to url for 3ds", ['redirect_url' => $redirectUrl]);
        }
        else {
            Log::warning("Checkout payment failed", [$paymentResponse]);
            return ResponseService::apiResponse(400, 'Payment is not successful. Please check your payment credentials and try again.');
        }
    }


    /** create a new account in JL
     *
     *  @param $request
     *  @param $transactionId
     *
     * @return array
     */
    public static function newAccount($request, $transactionId) : array
    {
        $newAccountResponse = Http::withHeaders([
            'Accept'             => 'application/json',
            'X-Verification-Key' => env('WEBHOOK_TOKEN'),
        ])->post(env('FRONTEND_URL') . "/api/v1/webhook/create-subscription", [
            "firstName"        => $request->first_name,
            "lastName"         => $request->last_name ?? "FundedNext",
            "email"            => $request->email,
            "password"         => $request->password ?? "secret",
            "planId"           => $request->plan_id,
            "server_name"      => $request->server_name,
            "subscriptionType" => 2,
            "remarks"          => "Checkout payment: $transactionId",
            "country_id"       => $request->country_id ?? null,
        ]);

        if ($newAccountResponse->successful()) {
            $model = array(
                'properties' => array('login' => '', 'type' => "new account"),
            );
            $model = json_encode($model);
            $model = json_decode($model);
            self::audit("Checkout:purchase new account", $model);
            return ResponseService::basicResponse(200, 'Account is created successfully');
        } else {
            Log::error('JL un-successfully: ', [$newAccountResponse]);
            return ResponseService::basicResponse(400, "Something Went Wrong! We have already received your payment. Our team will provide your desired account soon.  We are very sorry for this unavailable circumstance. Please kindly knock in live chat.");
        }
    }


    /**
     * top-upReset call to JL
     *
     * @param Account $account
     * @param $type
     * @param $transactionId
     *
     * @return array
     */
    public static function topupReset(Account $account, $type, $transactionId)
    {
        $topUpOrResetResponse = Http::withHeaders([
            'Accept'             => 'application/json',
            'X-Verification-Key' => env('WEBHOOK_TOKEN'),
        ])->post(env('FRONTEND_URL') . "/api/v1/webhook/topup-reset-request", [
            "brokerNumber" => $account->id,
            "note"         => "Checkout:payment : $transactionId",
            "type"         => CheckoutService::type[$type],
        ]);
        if ($topUpOrResetResponse->successful()) {
            $model = array(
                'properties' => array('login' => $account->login, 'type' => CheckoutService::type[$type]),
            );
            $model = json_encode($model);
            $model = json_decode($model);
            self::audit("Checkout:purchase : " . CheckoutService::type[$type] , $model);
            return ResponseService::basicResponse(200, "Account " . CheckoutService::type[$type] . " successful");
        } else {
            Log::error("Topup/Reset error response from JL ", [$topUpOrResetResponse]);
            return ResponseService::basicResponse(400, 'Something Went Wrong! We have already received your payment. Our team will provide your desired account soon.  We are very sorry for this unavailable circumstance. Please kindly knock in live chat.');
        }
    }


    /**
     * coupon wise price minimization
     * @param $request
     *
     * @return array
     */
    public static function couponDiscountPrice(object $coupon, object $plan)
    {

        if ($coupon->type == AppConstants::COUPON_FLAT) {
            $payable_amount = $plan->price - $coupon->amount;
            $coupon_amount  = $coupon->amount;
        } else { // percentage
            $discount       = ($plan->price * ($coupon->amount / 100));
            $amount         = $plan->price - $discount;
            $payable_amount = $amount;
            $coupon_amount  = $discount;
        }
        $data = [
            'old_amount'     => $plan->price,
            'coupon_amount'  => $coupon_amount,
            'payable_amount' => $payable_amount,
        ];
        return $data;
    }


    /**
     * make 3ds payment or confirm payment and verify
     *
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public static function confirmPayment($request)
    {
        if ($request->type == AppConstants::PRODUCT_ORDER_NEW_ACCOUNT) {
            $plan = JlPlan::find($request->plan_id);
            $reference = $plan->name ."-"  . $request->type;
            $amount = $plan->price;
            if (isset($request->coupon_code)) {
                $coupon = CouponService::couponValidateCheck($request->coupon_code);
                if($coupon){
                    $couponDiscountPrice = CouponService::couponPrice($coupon, $plan);
                    $amount = $couponDiscountPrice['payable_amount'];
                    $reference = $reference . "-" . $coupon->coupon_code;
                }
            }
            $email = $request->email;
        } else {
            $account = Account::with('customer')->whereLogin($request->login)->first();
            $jlTradingAccount = JlTradingAccount::where('broker_number', $account->id)->first();
            $plan = JlPlan::find($jlTradingAccount->plan_id);
            $reference = $plan->name . "-"  . $request->type;
            if ($request->type == AppConstants::PRODUCT_ORDER_TOPUP) {
                $amount = $plan->topup_charge;
            } elseif ($request->type == AppConstants::PRODUCT_ORDER_RESET) {
                $amount = $plan->reset_charge;
            }
            $email = $account->customer->email;
        }

        $gatewayToken = (new CheckoutService)->getGatewayTokenFromGatewayId($request->gateway);
        if(AppConstants::GATEWAY_STRIPE == $request->gateway){
            $paymentResponse = PaymentHubService::requestPayment($request->name . $request->first_name .' '.$request->last_name, $request->email, $request->cko_session_id, $amount, $reference, $request->success_url, $request->fail_url, $gatewayToken);
        }
        else{
            $paymentResponse = Http::withHeaders([
                'Accept'        => 'application/json',
                'gateway-token' => config('ph-config.PH_GATEWAY_TOKEN'),
                'project-token' => config('ph-config.PH_PROJECT_TOKEN'),
            ])->post(config('ph-config.PH_URL') . "/3ds-payment-verify", [
                "cko_session_id" => $request->cko_session_id
            ]);
        }

        if ($paymentResponse->status() == 200 || $paymentResponse->status() == 202) {

            $decodeResponse = json_decode($paymentResponse->body());
            $transactionId = $decodeResponse->data->response->data->response->id;

            if($paymentResponse->status() == 202 && !Orders::where('transaction_id', $transactionId)->where('status', Orders::STATUS_PENDING)->exists()){
                Log::error("Transaction ID is already existed ", [$decodeResponse]);
                return ResponseService::apiResponse(400, $decodeResponse->message);
            }

            if ($request->type == AppConstants::PRODUCT_ORDER_NEW_ACCOUNT) {
                $response = self::newAccount($request, $transactionId);
            } else {
                $response = self::topupReset($account, $request->type, $transactionId);
            }

            if ($response['code'] == 200) {
                $orderData                  = new OrderData();
                $orderData->accountId       = isset($account) ? $account->id : null;
                $orderData->customerId      = isset($account->customer) ? $account->customer->id : null;
                $orderData->email           = $email;
                $orderData->orderType       = $request->type;
                $orderData->gateway         = $request->gateway;
                $orderData->serverName      = $request->server_name ?? AppConstants::TRADING_SERVER_MT4;
                $orderData->transactionId   = $transactionId;
                $orderData->jlPlanId        = $plan->id;
                $orderData->status          = Orders::STATUS_ENABLE;
                $orderData->couponId        = isset($coupon) ? $coupon->id : null;
                $orderData->billing_address = $request->billing_address ? json_encode($request->billing_address): null;
                if (isset($coupon) && isset($couponDiscountPrice)) {
                    $orderData->total     = $couponDiscountPrice['old_amount'] / 100;
                    $orderData->discount  = $couponDiscountPrice['coupon_amount'] / 100;
                    $orderData->gradTotal = $couponDiscountPrice['payable_amount'] / 100;
                } else {
                    $orderData->total     = $amount / 100;
                    $orderData->discount  = 0;
                    $orderData->gradTotal = $amount / 100;
                }

                $order = (new OrderService())->generateOrder($orderData);

                if ($orderData->orderType == AppConstants::PRODUCT_ORDER_NEW_ACCOUNT) {
                    AffiliateApiCallJob::dispatch($orderData)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
                }

                /**
                 * Dispatching order email with invoice pdf attachment.
                */
                InvoiceGenerateJob::dispatch($order->id, $request->type)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
                return ResponseService::apiResponse(200, $response['message']);
            }
            return ResponseService::apiResponse(400, $response['message']);
        }
        elseif($paymentResponse->status() == 400){
            $decodeResponse = json_decode($paymentResponse->body());
            $transactionId = $decodeResponse->data->response->data->response->id;
            Orders::where('transaction_id', $transactionId)->update(['status' => Orders::STATUS_DISABLE, 'remarks' => $decodeResponse->message]);
            Log::warning("Checkout payment failed", [$decodeResponse]);
            return ResponseService::apiResponse(400, $decodeResponse->message);
        }
        else {
            $decodeResponse = json_decode($paymentResponse->body());
            Log::warning("Checkout payment failed because of exception", [$decodeResponse]);
            return ResponseService::apiResponse(400, $decodeResponse->message, [
                'order_disabled_status'=> true
            ]);
        }
    }
    public function getGatewayTokenFromGatewayId($id){
        if($id == AppConstants::GATEWAY_CHECKOUT){
            return config('ph-config.PH_GATEWAY_TOKEN');
        }elseif ($id == AppConstants::GATEWAY_STRIPE){
            return config('ph-config.PH_GATEWAY_TOKEN_STRIPE');
        }else{
            return config('ph-config.PH_GATEWAY_TOKEN');
        }
    }

    /**
     *  verify payment id in checkout
     * @param string $ck_session_id
     * @return PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function paymentVerify( string $ck_session_id){
        return Http::withHeaders([
            'Accept'        => 'application/json',
            'gateway-token' => config('ph-config.PH_GATEWAY_TOKEN'),
            'project-token' => config('ph-config.PH_PROJECT_TOKEN'),
        ])->post(config('ph-config.PH_URL') . "/3ds-payment-verify", [
            "cko_session_id" => $ck_session_id
        ]);
    }

}
