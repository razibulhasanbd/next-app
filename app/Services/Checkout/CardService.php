<?php

namespace App\Services\Checkout;

use App\Constants\AppConstants;
use App\DataSource\OrderData;
use App\Jobs\AffiliateApiCallJob;
use App\Models\Account;
use App\Models\JlPlan;
use App\Models\JlTradingAccount;
use App\Models\Orders;
use App\Services\OrderService;
use App\Services\ResponseService;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CardService
{
    /**
     * @param string $email
     * @return Response|void
     */
    public static function cardInfo(string $email)
    {
        $cardResponse = PaymentHubService::requestCardInfo($email);
        if ($cardResponse->successful()) {
            return ResponseService::apiResponse(200, '',
                $cardResponse->body()
            );
        }
    }

    /**
     * @param string $email
     * @return PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function getCardList(string $email)
    {
        return Http::withHeaders([
            'Accept'            => 'application/json',
            'communication-key' => config('ph-config.COMMUNICATION_KEY'),
        ])->get(config('ph-config.PH_URL') . "/get-all-cards",
            ['email' => $email]);
    }


    /**
     * update card
     * @param int $id
     * @param int $is_primary
     * @return PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function makePrimary(int $id, int $is_primary)
    {
        return Http::withHeaders([
            'Accept'            => 'application/json',
            'communication-key' => config('ph-config.COMMUNICATION_KEY'),
        ])->get(config('ph-config.PH_URL') . "/make-primary-card",
            ['id' => $id, 'is_primary' => $is_primary]);
    }

    /**
     * card delete
     * @param int $id
     * @param int $is_deleted
     * @return PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function cardDelete(int $id, int $is_deleted)
    {
        return Http::withHeaders([
            'Accept'            => 'application/json',
            'communication-key' => config('ph-config.COMMUNICATION_KEY'),
        ])->get(config('ph-config.PH_URL') . "/disable-card",
            ['id' => $id, 'is_deleted' => $is_deleted]);
    }

    /**
     * create account with existing card
     * @param $request
     * @return Response
     */
    public function confirmCardPayment($request)
    {
        if ($request->type == AppConstants::PRODUCT_ORDER_NEW_ACCOUNT) {

            $plan      = JlPlan::find($request->plan_id);
            $reference = $plan->name . "-" . $request->type;
            $amount    = $plan->price;
            if (isset($request->coupon_code)) {
                $coupon = CouponService::couponValidateCheck($request->coupon_code);
                if ($coupon) {
                    $couponDiscountPrice = CouponService::couponPrice($coupon, $plan);
                    $amount              = $couponDiscountPrice['payable_amount'];
                    $reference           = $reference . "-" . $coupon->coupon_code;
                }
            }

        } else {
            $account          = Account::with('customer')->whereLogin($request->login)->first();
            $jlTradingAccount = JlTradingAccount::where('broker_number', $account->id)->first();
            $plan             = JlPlan::find($jlTradingAccount->plan_id);
            $reference        = $plan->name . "-" . $request->type;
            if ($request->type == AppConstants::PRODUCT_ORDER_TOPUP) {
                $amount = $plan->topup_charge;
            } elseif ($request->type == AppConstants::PRODUCT_ORDER_RESET) {
                $amount = $plan->reset_charge;
            }
        }
        $gatewayToken    = (new CheckoutService)->getGatewayTokenFromGatewayId($request->gateway);
        $paymentResponse = (new PaymentHubService())->makeCardPayment($request->id, $amount, $reference, $gatewayToken);
        if ($paymentResponse->status() == 200 || $paymentResponse->status() == 202) {

            $decodeResponse = json_decode($paymentResponse->body());
            $transactionId  = $decodeResponse->data->response->data->response->id;

            if ($paymentResponse->status() == 202 && !Orders::where('transaction_id', $transactionId)->where('status', Orders::STATUS_PENDING)->exists()) {
                Log::error("Transaction ID is already existed ", [$decodeResponse]);
                return ResponseService::apiResponse(400, $decodeResponse->message);
            }

            if ($request->type == AppConstants::PRODUCT_ORDER_NEW_ACCOUNT) {
                $response = CheckoutService::newAccount($request, $transactionId);
            } else {
                $response = CheckoutService::topupReset($account, $request->type, $transactionId);
            }
            
            if ($response['code'] == 200) {
                $orderData                = new OrderData();
                $orderData->accountId     = null;
                $orderData->customerId    = null;
                $orderData->email         = $request->email;
                $orderData->orderType     = AppConstants::PRODUCT_ORDER_NEW_ACCOUNT;
                $orderData->gateway       = $request->gateway;
                $orderData->transactionId = $transactionId;
                $orderData->status        = Orders::STATUS_ENABLE;
                $orderData->jlPlanId      = $plan->id;
                $orderData->remarks       = null;
                $orderData->couponId      = isset($coupon) ? $coupon->id : null;
                if (isset($coupon) && isset($couponDiscountPrice)) {
                    $orderData->total     = $couponDiscountPrice['old_amount'] / 100;
                    $orderData->discount  = $couponDiscountPrice['coupon_amount'] / 100;
                    $orderData->gradTotal = $couponDiscountPrice['payable_amount'] / 100;
                } else {
                    $orderData->total     = $amount / 100;
                    $orderData->discount  = 0;
                    $orderData->gradTotal = $amount / 100;
                }
                (new OrderService())->generateOrder($orderData, 'manual');
                if ($request->type == AppConstants::PRODUCT_ORDER_NEW_ACCOUNT) {
                    AffiliateApiCallJob::dispatch($orderData)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
                }
                return ResponseService::apiResponse(200, $response['message']);
            }
            return ResponseService::apiResponse(400, $response['message']);
        }
        Log::error("card account create error ", [$paymentResponse->body()]);
        return ResponseService::apiResponse(400, "Something went wrong! please contact with live chat");
    }

}
