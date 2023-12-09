<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Constants\AppConstants;
use App\Http\Controllers\Controller;
use App\Services\Checkout\CheckoutService;
use App\Services\OrderService;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function paymentHistory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
//                'email' => 'required|email|max:100',
                'account_id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
            }

            $account = getAuthenticateAccount($request->account_id);

            if (!$account) {
                return ResponseService::apiResponse(404, 'Account not found');
            }

            $orders = (new OrderService)->orderHistory($account->customer->email, $account->id);
            $order  = [];
            if ($orders) {
                foreach ($orders as $item) {
                    $order [] = [
                        'id'             => $item->id,
                        'payment_method' => paymentGateways()[$item->gateway] ?? null,
                        'transaction_id' => $item->transaction_id,
                        'total'          => $item->total,
                        'discount'       => $item->discount,
                        'grand_total'    => $item->grand_total,
                        'date'           => $item->updated_at,
                        'order_type'     => CheckoutService::type[$item->order_type] ?? null,
                    ];
                }

            }

            return ResponseService::apiResponse(200, 'payment history', $order);
        } catch (Exception $exception) {
            return ResponseService::apiResponse(500, "Internal server error");
        }
    }

    public function pendingPaymentHistory(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:100'
            ]);

            if ($validator->fails())
            {
                return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
            }

            $orders = (new OrderService)->pendingPaymentHistory($request->email);
            return ResponseService::apiResponse(200, 'pending payment history', $orders);
        }
        catch (Exception $exception)
        {
            return ResponseService::apiResponse(500, "Internal server error");
        }
    }
}
