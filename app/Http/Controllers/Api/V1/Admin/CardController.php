<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Constants\AppConstants;
use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Plan;
use App\Services\Checkout\CardService;
use App\Services\Checkout\CheckoutService;
use App\Services\Checkout\PaymentHubService;
use App\Services\OrderService;
use App\Services\ResponseService;
use App\Services\Stripe\StripeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    public function addCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gateway' => 'required|in:' . AppConstants::GATEWAY_CHECKOUT,
            'token'   => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
        }
        $customer = getAuthCustomer();

        if (!$customer) {
            return ResponseService::apiResponse(404, 'Customer not found');
        }

        if ($request->gateway == AppConstants::GATEWAY_CHECKOUT) {
            $gatewayToken    = (new CheckoutService)->getGatewayTokenFromGatewayId($request->gateway);
            $paymentResponse = PaymentHubService::requestPayment($customer->name, $customer->email, $request->token, 0, 'add_card', $request->success_url, $request->fail_url, $gatewayToken);

            if ($paymentResponse->status() == 202) { // 3ds payment
                $decodeResponse = json_decode($paymentResponse->body());
                $redirectUrl    = $decodeResponse->data->response->data->redirect_url;
                return ResponseService::apiResponse(202, "Redirect to url for 3ds", ['redirect_url' => $redirectUrl]);
            } else {
                Log::warning("Checkout card add failed", [$paymentResponse]);
                return ResponseService::apiResponse(400, 'card add failed. Please check your payment credentials and try again.');
            }

        }

    }

    public function confirmCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cko_session_id' => 'required|string',
            'gateway'        => 'required|in:' . AppConstants::GATEWAY_CHECKOUT . ',' . AppConstants::GATEWAY_STRIPE,
            'payment_intent_id'     => 'required_if:gateway,' . AppConstants::GATEWAY_STRIPE,
        ]);

        if ($validator->fails()) {
            return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
        }
        if ($request->gateway == AppConstants::GATEWAY_CHECKOUT) {
            $response = (new CheckoutService)->paymentVerify($request->cko_session_id);
            if ($response->status() == 200) {
                return ResponseService::apiResponse(200, 'Card successfully added');
            } elseif ($response->status() == 202) {
                $decodeResponse = json_decode($response->body());
                return ResponseService::apiResponse(400, $decodeResponse->message);
            }
        } else {
            $response = (new StripeService())->cardAddRequest($request->cko_session_id, $request->payment_intent_id);
            if ($response->status() == 200) {
                return ResponseService::apiResponse(200, 'Card successfully added');
            }
        }
        Log::warning("Checkout card add failed", [$response->body()]);
        return ResponseService::apiResponse(400, "Card add failed. please try again or provide another gateway");
    }

    public function cardList(Request $request)
    {
        try {

            $customer = getAuthCustomer();

            if (!$customer) {
                return ResponseService::apiResponse(404, 'Customer not found');
            }

            $response = (new CardService)->getCardList($customer->email);
            if ($response->status() == 200) {
                $response = json_decode($response->body());
                return ResponseService::apiResponse(200,  'Get All Customer Cards',$response->data);
            }
            Log::error("card fetch failed", [$response->body()]);
            return ResponseService::apiResponse(402, 'something went wrong. kindly please knock in live chat');

        } catch (Exception $exception) {
            Log::error($exception);
            return ResponseService::apiResponse(500, "Internal server error");
        }
    }

    public function makePrimary(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'is_primary'        => 'required|in:0,1',
            ]);
            if ($validator->fails()) {
                return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
            }
            $response = (new CardService)->makePrimary($request->id, $request->is_primary);
            if ($response->status() == 200) {
                return ResponseService::apiResponse(200,  "Update successfully");
            }
            Log::error("card update failed", [$response->body()]);
            return ResponseService::apiResponse(402, 'something went wrong. kindly please knock in live chat');

        } catch (Exception $exception) {
            Log::error($exception);
            return ResponseService::apiResponse(500, "Internal server error");
        }
    }

    public function cardDelete(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'id'         => 'required|integer',
                'is_deleted' => 'required|in:0',
            ]);
            if ($validator->fails()) {
                return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
            }
            $response = (new CardService)->cardDelete($request->id, $request->is_deleted);
            if ($response->status() == 200) {
                return ResponseService::apiResponse(200, "Deleted successfully");
            }
            Log::error("card delete failed", [$response->body()]);
            return ResponseService::apiResponse(402, 'something went wrong. kindly please knock in live chat');

        } catch (Exception $exception) {
            Log::error($exception);
            return ResponseService::apiResponse(500, "Internal server error");
        }
    }
    public function cardPayment(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'id'      => 'required|integer',
                'type'    => 'required|in:' . AppConstants::PRODUCT_ORDER_NEW_ACCOUNT . ',' . AppConstants::PRODUCT_ORDER_TOPUP . ',' . AppConstants::PRODUCT_ORDER_RESET,
                'login'   => 'required_unless:type,' . AppConstants::PRODUCT_ORDER_NEW_ACCOUNT,
                'gateway' => 'required|in:' . AppConstants::GATEWAY_CHECKOUT . ',' . AppConstants::GATEWAY_STRIPE,
                'plan_id' => 'required|exists:' . AppConstants::DATABASE_CONNECTION_FUNDED_NEXT_BACKEND . '.plans,id',
                'email'          => 'required|email',
            ]);
            if ($validator->fails()) {
                return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
            }

            return (new CardService)->confirmCardPayment($request);
        } catch (Exception $exception) {
            Log::error($exception);
            return ResponseService::apiResponse(500, "Internal server error");
        }
    }
}
