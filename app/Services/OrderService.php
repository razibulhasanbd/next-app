<?php

namespace App\Services;

use App\Helper\Helper;
use App\Jobs\EmailJob;
use App\Models\JlPlan;
use App\Models\Orders;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Typeform;
use App\DataSource\OrderData;
use App\Jobs\OrderGenerateJob;
use App\Constants\AppConstants;
use App\Jobs\InvoiceGenerateJob;
use App\Constants\EmailConstants;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\Checkout\CouponService;
use App\Http\Controllers\AccountController;

class OrderService
{
    /**
     * order generate job
     *
     * @param OrderService $orderData
     * @return void
     */
    public function orderGenerateJob(OrderData $orderData): void
    {
        OrderGenerateJob::dispatch($orderData)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
    }


    /**
     * generate order
     *
     * @param OrderData $orderData
     * @param $type
     *
     * @return Orders $order
     */
    public function generateOrder(OrderData $orderData)
    {
        if ($orderData->accountId == null && $orderData->status == Orders::STATUS_ENABLE) {
            $customerWithNewAccount = $this->getCustomerWithLatestAccount($orderData->email);
            $orderData->customerId  = $customerWithNewAccount->id ?? null;
            $orderData->accountId   = $customerWithNewAccount->latestAccount->id ?? null;
        }

        if ($orderData->gateway == AppConstants::FREE_ACCOUNT) { // for manual account creation
            $order = new Orders();
        } else {
            $order = Orders::where('transaction_id', $orderData->transactionId)->first();
            if (!$order) {
                $order = new Orders();
            }
        }

        $order->account_id      = $orderData->accountId;
        $order->customer_id     = $orderData->customerId;
        $order->coupon_id       = $orderData->couponId;
        $order->order_type      = $orderData->orderType;
        $order->gateway         = $orderData->gateway;
        $order->transaction_id  = $orderData->transactionId;
        $order->total           = $orderData->total;
        $order->discount        = $orderData->discount;
        $order->grand_total     = $orderData->gradTotal;
        $order->jl_plan_id      = $orderData->jlPlanId;
        $order->parent_order_id = $orderData->parentOrderId ?? null;
        $order->status          = $orderData->status;
        $order->server_name     = $orderData->serverName;
        $order->remarks         = $orderData->remarks ?? null;
        if (isset($orderData->billing_address)) {
            $order->billing_address = $orderData->billing_address;
        }
        $order->created_by      = Auth::user() ? Auth::user()->id : null;
        $order->save();

        return $order;
    }


    /**
     * get the customer and his new account
     *
     * @param string $email
     * @return Customer|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     */
    private function getCustomerWithLatestAccount(string $email)
    {
        return Customer::with('latestAccount')->where('email', $email)->first();
    }


    /**
     * return customer name
     * @param $email
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function getCustomerInfo($email)
    {
        $customer = Customer::where('email', $email)->first();
        if (!$customer) {
            return response()->json(['status' => false, 'message' => 'Customer not found', 'data' => []]);
        }
        $exp        = explode(' ', $customer->name);
        $first_name = '';
        $last_name  = '';
        if (count($exp) == 2) {
            $first_name = $exp[0];
            $last_name  = $exp[1];
        } elseif (count($exp) > 2) {
            $first_name = $exp[0] . ' ' . $exp[1];
            $last       = $exp[3] ?? "";
            $last_name  = $exp[2] . ' ' . $last;
        }
        return response()->json(['status' => true, 'data' => ['first_name' => $first_name, 'last_name' => $last_name, 'country_id' => $customer->country_id]]);
    }


    /**
     * Order Create from backend
     * @param $request
     * @return JsonResponse
     */
    public function orderCreateBackend($request)
    {
        $plan = JlPlan::find($request->plan_id);
        $amount = $plan->price;
        if ($request->payment_gateway_id == AppConstants::FREE_ACCOUNT) {
            $order  = null;
        } else {
            $order  = Orders::where('transaction_id', $request->transaction_id)->where('status', Orders::STATUS_ENABLE)->first();
            if (isset($request->coupon_code)) {
                $coupon = CouponService::couponValidateCheck($request->coupon_code);
                if ($coupon) {
                    $couponDiscountPrice = CouponService::couponPrice($coupon, $plan);
                    $amount              = $couponDiscountPrice['payable_amount'];
                }
            }
        }

        $orderData                = new OrderData();
        $orderData->accountId     = null;
        $orderData->customerId    = null;
        $orderData->email         = $request->email;
        $orderData->orderType     = AppConstants::PRODUCT_ORDER_NEW_ACCOUNT;
        $orderData->gateway       = $request->payment_gateway_id;
        $orderData->transactionId = $request->transaction_id;
        $orderData->status        = Orders::STATUS_ENABLE;
        $orderData->jlPlanId      = $plan->id;
        $orderData->remarks       = $request->remarks;
        $orderData->serverName       = $request->server_name;
        $orderData->couponId = isset($coupon) ? $coupon->id : null;
        if (isset($coupon) && isset($couponDiscountPrice)) {
            $orderData->total     = $couponDiscountPrice['old_amount'] / 100;
            $orderData->discount  = $couponDiscountPrice['coupon_amount'] / 100;
            $orderData->gradTotal = $couponDiscountPrice['payable_amount'] / 100;
        } else {
            $orderData->total     = $amount / 100;
            $orderData->discount  = 0;
            if ($request->payment_gateway_id == AppConstants::FREE_ACCOUNT) {
                $orderData->gradTotal = 0;
            } else {
                $orderData->gradTotal = $amount / 100;
            }
        }

        if ($order) {
            $notBreachedAccount = Account::where('id', $order->account_id)->where('breached', 0)->first();
            if ($notBreachedAccount) {
                $account            = new AccountController();
                $account->breachEvent($notBreachedAccount, 'admin');
            }
            $orderData->parentOrderId = $order->id;
            $order->status            = Orders::STATUS_DISABLE;
            $order->save();
        }
         $newOrder = (new OrderService())->generateOrder($orderData);
        /**
         * Dispatching order email.
         */
        $newOrder->load('customer','account.server','jlPlans');
        $details = [
            'template_id'          => EmailConstants::NEW_SUBSCRIPTION_MAIL,
            'to_name'              => Helper::getOnlyCustomerName($newOrder->customer->name),
            'to_email'             => $request->email,
            'email_body' => [
                "name" => Helper::getOnlyCustomerName($newOrder->customer->name),
                "plan" => $newOrder->jlPlans->name,
                "mt4_login_id"=> $newOrder->account->login,
                "mt4_login_password" => $newOrder->account->password,
                "mt4_server_id" => $newOrder->account->server->friendly_name,
                "trustpilot_url" => EmailConstants::TRUSTPILOT_URL
                ]
        ];
        EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
        // InvoiceGenerateJob::dispatch($newOrder->id, $newOrder->order_type)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
        return response()->json(['status' => true, 'data' => [], 'message' => "Successfully order generate"]);
    }

    /**
     * return order history
     * @param string $email
     * @return mixed
     */
    public function orderHistory(string $email, int $account_id)
    {

        return Orders::whereHas('customer', function ($q) use ($email) {
            $q->where('email', $email);
        })->where('status', Orders::STATUS_ENABLE)->where('account_id', $account_id)->orderBy('created_at', 'DESC')->get();
    }

    /**
     * return pending payment history
     * @param string $email
     * @return mixed
     */
    public function pendingPaymentHistory(string $email){

        return Typeform::where('email', $email)
                ->where('approved_at', null)
                ->where('denied_at', null)
                ->orderBy('created_at', 'DESC')
                ->get();
    }
}
