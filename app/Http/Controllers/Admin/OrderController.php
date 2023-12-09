<?php

namespace App\Http\Controllers\Admin;

use App\Constants\AppConstants;
use App\Models\Orders;
use App\Services\Checkout\CheckoutService;
use App\Services\Checkout\CouponService;
use App\Services\Checkout\PaymentHubService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\JlPlan;
use App\Models\RefundRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{

    public function index(Request $request){
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = Orders::with('customer','account', 'jlPlans', 'coupon','refundRequestApprove','addCharges')->select(sprintf('%s.*', (new Orders())->table));
            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'order_show';
                $editGate = '';
                $deleteGate = '';
                $crudRoutePart = 'orders';
                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id  : '';
            });
            $table->editColumn('customer.email', function ($row) {
                return  $row->customer ? '<a href="' . route('admin.customers.show', $row->customer->id) . '" target=_blank>' . $row->customer->email . '</a>' : '';
            });

            $table->editColumn('account.login', function ($row) {
                if((isset($row->addCharges) && count($row->addCharges) > 0) && (isset($row->refundRequest) && count($row->refundRequest) > 0) ){
                    return $row->account ? '<a href=' . route('admin.accounts.show', $row->account->id) . ' target=_blank>' . $row->account->login . '</a>' .' <i class="fa fa-dot-circle-o" title="Has Charge" style="color:green"></i>' .' <i class="fa fa-circle" title="Has Refund" style="color:red"></i>'  : '';
                }
                elseif(isset($row->addCharges) && count($row->addCharges) > 0){
                    return $row->account ? '<a href=' . route('admin.accounts.show', $row->account->id) . ' target=_blank>'  . $row->account->login . '</a>' .' <i class="fa fa-dot-circle-o" title="Has Charge" style="color:green"></i>' : '';
                }elseif(isset($row->refundRequest) && count($row->refundRequest) > 0){
                    return $row->account ? '<a href=' . route('admin.accounts.show', $row->account->id) . ' target=_blank>'   . $row->account->login . '</a>' .' <i class="fa fa-circle" title="Has Refund" style="color:red"></i>' : '';
                }else{
                    return $row->account ? '<a href=' . route('admin.accounts.show', $row->account->id) . ' target=_blank>' . $row->account->login . '</a>' : '';
                }
            });

            $table->editColumn('server_name', function ($row) {
                return $row->server_name ? $row->server_name : '';
            });

            $table->editColumn('jlPlans.name', function ($row) {
                return $row->jlPlans? $row->jlPlans->name : '' ;
            });
            $table->editColumn('coupon.name', function ($row) {
                return $row->coupon ? '<a href=' . route('admin.coupons.show', $row->coupon->id) . ' target=_blank>' . $row->coupon->name . '</a>' : '';
            });

            $table->editColumn('order_type', function ($row) {
                return $row->order_type ? CheckoutService::type[$row->order_type] : '';
            });
            $table->editColumn('gateway', function ($row) {
                return $row->gateway ? paymentGateways()[$row->gateway] : '';
            });
            $table->editColumn('transaction_id', function ($row) {
                return $row->transaction_id ? $row->transaction_id : '';
            });

            $table->editColumn('total', function ($row) {
                return $row->total ? $row->total : '';
            });
            $table->editColumn('discount', function ($row) {
                return $row->discount ? $row->discount : '';
            });
            $table->editColumn('grand_total', function ($row) {
                if((isset($row->addCharges) && count($row->addCharges) > 0) && (isset($row->refundRequestApprove) && count($row->refundRequestApprove) > 0) ){
                    $netAmount = $row->grand_total + $row->addCharges->sum('amount') - $row->refundRequestApprove->sum('amount');
                    return $netAmount;
                }
                elseif(isset($row->addCharges) && count($row->addCharges) > 0){
                    $netAmount = $row->grand_total + $row->addCharges->sum('amount');
                    return $netAmount;
                }elseif(isset($row->refundRequestApprove) && count($row->refundRequestApprove) > 0){
                    $netAmount = $row->grand_total - $row->refundRequestApprove->sum('amount');
                }else{
                    $netAmount = $row->grand_total;
                    return $netAmount;
                }
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? $row->status : 0;
            });



            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at : "";
            });
            $table->editColumn('updated_at', function ($row) {
                return $row->updated_at ? $row->updated_at : "";
            });

            $table->rawColumns(['customer.email','account.login','coupon.name','jlPlans.name','created_at','updated_at','placeholder','actions']);


            return $table->make(true);
        }
        $coupons = Coupon::pluck('name','id');

        return view('admin.orders.index',compact('coupons'));
    }

    public function create(Request $request){

        $plans = JlPlan::select('name', 'id','price')->get();
        $countries = Country::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $coupons = Coupon::where('expiry_date', '>', Carbon::now() )->pluck('name', 'code')->prepend(trans('global.pleaseSelect'), '');
        $transactionId=[];
        $paymentGatewayId=["" => "Select One"]+paymentGateways();
        return view('admin.orders.create',compact('plans','countries','coupons','transactionId','paymentGatewayId'));
    }

    public function show(Orders $order)
    {
        abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $order->load('account','customer','coupon','refundRequestApprove.user','addCharges.user');

        $orderType= CheckoutService::type[$order->order_type];
        $gateway= paymentGateways();
        $gateway= $gateway[$order->gateway];
        $netAmount = 0;
        if (isset($order->addCharges) && count($order->addCharges) > 0 && (isset($order->refundRequestApprove) && count($order->refundRequestApprove) > 0)) {
            $netAmount = $order->total + $order->addCharges->sum('amount') - $order->refundRequestApprove->sum('amount');
        } elseif (isset($order->addCharges) && count($order->addCharges) > 0) {
            $netAmount = $order->total + $order->addCharges->sum('amount');
        } elseif (isset($order->refundRequestApprove) && count($order->refundRequestApprove) > 0) {
            $netAmount = $order->total - $order->refundRequestApprove->sum('amount');
        } else {
            $netAmount = $order->total;
        }
        return view('admin.orders.show', compact('order','gateway', 'orderType','netAmount'));
    }

    public function customerInfo(Request $request)
    {
        try {
            return (new OrderService)->getCustomerInfo($request->email);
        } catch (\Exception $exception) {
            Log::error($exception);
            return response()->json(['status'=> false, 'data'=> [], 'message'=> 'Internal server error']);
        }

    }

    public function couponInfo(Request $request){
        try {
            if ($coupon = CouponService::couponValidateCheck($request->coupon_code)) {

                $plan = JlPlan::find($request->plan_id);
                $amountDetails = CouponService::couponPrice($coupon, $plan);
                return response()->json(['status'=> true, 'amount'=>  $amountDetails['payable_amount']/100,
                                         'discount' => $amountDetails['coupon_amount']/100, 'old_amount'=>  $amountDetails['old_amount']/100]);
            }
            return response()->json(['status'=> false, 'data'=> [], 'message'=> 'Coupon code not match']);
        } catch (\Exception $exception) {
            Log::error($exception);
            return response()->json(['status'=> false, 'data'=> [], 'message'=> 'Internal server error'], 500);
        }
    }

    public function transactionIdVerify(Request $request)
    {
        try {
            $message = "";
            $status  = false;
            $order   = Orders::where('transaction_id', $request->transaction_id)->where('status', Orders::STATUS_ENABLE)->first();
            if ($order) {
                $status  = true;
                $message = "Existing Transaction Id found and old order will be disabled";
            }
            return response()->json(['status' => $status, 'data' => [], 'message' => $message]);
        } catch (\Exception $exception) {
            Log::error($exception);
            return response()->json(['status' => false, 'data' => [], 'message' => 'Internal server error'], 500);
        }
    }

    public function orderCreate(Request $request)
    {
        $this->validate($request, [
            'email'              => 'required|email',
            'first_name'         => 'required|max:200',
            'last_name'          => 'nullable|max:200',
            'payment_gateway_id' => 'required|integer',
            'transaction_id'     => 'required_unless:payment_gateway_id,' . AppConstants::FREE_ACCOUNT,
            'country_id'         => 'required|integer',
            'plan_id'            => 'required|exists:' . AppConstants::DATABASE_CONNECTION_FUNDED_NEXT_BACKEND . '.plans,id',
            'remarks'            => 'nullable|string',
            'coupon_code'        => 'nullable|string',
            'password'           => 'nullable|string|max:100',
            'total'              => 'required',
            'server_name'        => ['required', Rule::in([AppConstants::TRADING_SERVER_MT4,AppConstants::TRADING_SERVER_MT5])],
        ]);

        Log::info("orderCreate(): ",[$request->all()]);
        try {
            if($request->payment_gateway_id == AppConstants::GATEWAY_OUTSIDE){ // 2 = outside payment

                return $this->orderAccountCreate($request, $request->transaction_id);
            }elseif($request->payment_gateway_id == AppConstants::FREE_ACCOUNT){ // 4
                return $this->orderAccountCreate($request, 'Free Account');
            }else{
                $response = (new PaymentHubService())->paymentHubTraCheck($request->transaction_id);
                if ($response->successful()) {
                    $responseDecode = json_decode($response->body());
                    if ($responseDecode->data->data->status) {
                       return $this->orderAccountCreate($request, $request->transaction_id);
                    }
                }
            }

            Log::error('Manual order creation failed', [$response]);
            return response()->json(['status' => false, 'data' => [], 'message' => 'The transaction is not found'], 404);
        } catch (Exception $exception) {
            Log::error($exception);
            return response()->json(['status' => false, 'data' => [], 'message' => 'Internal server error'],500);
        }

    }

    /**
     * @param $request
     * @param $transaction_id
     * @return JsonResponse|void
     */
    protected function orderAccountCreate($request, $transaction_id){
        $response = CheckoutService::newAccount($request, $transaction_id);
        if ($response['code'] == 200) {
            return (new OrderService)->orderCreateBackend($request);
        }
    }

}
