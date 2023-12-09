<?php

namespace App\Console\Commands;

use App\Constants\AppConstants;
use App\Constants\CommandConstants;
use App\Helper\Helper;
use App\Models\Orders;
use App\Services\Checkout\CheckoutService;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PendingOrdersCrossCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = CommandConstants::Pending_Orders_CrossCheck_Command;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will cross check the pending order older than 10 minutes.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = Orders::with('customer', 'account', 'coupon')->oldPendingOrders(20)->limit(50)->get();
        Log::info("Order pending cross check has started. Total pending orders ". $orders->count());
        $checkoutService = new CheckoutService();
        foreach ($orders as $order) {
            $request = new Request();
            $request->merge(
                [
                    'type'           => $order->order_type,
                    'plan_id'        => $order->jl_plan_id,
                    'coupon_code'    => $order->coupon?->code,
                    'email'          => $order->customer->email,
                    'name'           => $order->customer->name,
                    'cko_session_id' => $order->transaction_id,
                    'login'          => $order->account?->login,
                    'gateway'        => $order->gateway,
                    'server_name'    => $order->server_name,
                ]
            );
            $response = $checkoutService->confirmPayment($request);
            if($response->status() == 200){
                Log::info("Robot generates the pending order successfully", ["Order ID " => $order->id, "Server response " => json_decode($response->content())]);
                Helper::discordAlert(
                    "**Robot generates the pending order successfully **:\nOrderId : " . $order->id
                    . "\nTransactionID: " .  $order->transaction_id
                );
            }
            elseif($response->status() == 429){
                Log::warning("Rate limit error", ["Order ID " => $order->id, "Server response " => $response]);
            }
            elseif($response->status() == 400){
                $response = json_decode($response->content());
                $data = $response->data;
                if(isset($data->order_disabled_status) && $data->order_disabled_status){
                    Orders::where('id', $order->id)->update(['status' => Orders::STATUS_DISABLE, 'remarks' => $response->message]);
                    Log::warning("Robot can not update the order", ["Order ID " => $order->id, "Server response " => $response]);
                }
            }
            else{
                $response = json_decode($response->content());
                Orders::where('id', $order->id)->update(['status' => Orders::STATUS_DISABLE, 'remarks' => $response->message]);
                Log::warning("Robot can not update the order", ["Order ID " => $order->id, "Server response " => $response]);
            }
            sleep(1);
        }

        Log::info("Order pending cross check finished.");
    }
}
