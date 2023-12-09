<?php

namespace App\Http\Controllers\Admin;

use config;
use App\Models\Orders;
use Illuminate\Http\Request;
use App\Models\RefundRequest;
use App\Services\RefundService;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\StoreRefundReq;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;

class RefundRequestController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = RefundRequest::with('user', 'order')->select(sprintf('%s.*', (new RefundRequest())->table));
            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'order_show';
                $editGate = '';
                $deleteGate = '';
                $crudRoutePart = 'refunds';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('order.transaction_id', function ($row) {
                return  $row->order ? '<a href="' . route('admin.orders.show', $row->order->id) . '" target=_blank>' . $row->order->transaction_id . '</a>' : '';
            });
            $table->editColumn('user.name', function ($row) {
                return $row->user ? '<a href=' . route('admin.customers.show', $row->user->id) . ' target=_blank>' . $row->user->name . '</a>' : '';
            });
            $table->editColumn('order_id', function ($row) {
                return $row->order_id ?? $row->order_id;
            });

            $table->editColumn('status', function ($row) {
                return $row->status ? $row->status : 0;
            });
            $table->editColumn('amount', function ($row) {
                return $row->amount ? $row->amount : '';
            });
            $table->editColumn('comment', function ($row) {
                return $row->comment ? $row->comment : '';
            });
            $table->editColumn('reply_comment', function ($row) {
                return $row->reply_comment ? $row->reply_comment : '';
            });


            $table->rawColumns(['order.transaction_id', 'user.name', 'order_id', 'status', 'amount', 'comment', 'reply_comment', 'placeholder', 'actions']);

            return $table->make(true);
        }

        return view('admin.refunds.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $order = Orders::find($id);
        return view('admin.refunds.create', compact('order'));
    }


    public function callRefundReqApi(StoreRefundReq $request)
    {
        $orderInfo = Orders::find($request->order_id);
        try {
            $response = Http::withHeaders([
                'Accept'            => 'application/json',
                'communication-key' => config('ph-config.COMMUNICATION_KEY'),
            ])->post(config('ph-config.PH_URL') . "/refunds/request", [
                "transaction_id" => $orderInfo->transaction_id,
                "amount"         => $request->amount,
                "comment"        => $request->comment,
                "callback_url"   => config('app.url') . "api/refunds/get-update",
                "vendor_data"    => [
                    "order_id" => $orderInfo->id,
                ],
            ]);

            if ($response->successful()) {
                (new RefundService)->storeRefund(
                    $response['data']['refund']['vendor_data']['order_id'],
                    $response['data']['refund']['amount'],
                    $response['data']['refund']['status'],
                    $response['data']['refund']['comment'],
                    $response['data']['refund']['reply_comment']

                );
                return redirect()->route('admin.refunds.index')->with('message', 'Refund Store Successfully');
            } else {
                return back()->with('warning', $response['message']);
            }
        } catch (\Exception $exception) {
            Log::error($exception);
            return response()->json(['status' => false, 'data' => [], 'message' => 'Internal server error']);
        }
    }

    public function webhookRefundCallBack(Request $request)
    {
        if (isset($request->vendor_data['order_id'])) {
            try {
                return (new RefundService)->updateRefundRequest($request->vendor_data['order_id'], $request->refund_data['status'], $request->refund_data['reply_comment']);
                
            } catch (\Exception $exception) {
                Log::error($exception);
                return response()->json(['status' => false, 'data' => [], 'message' => 'Internal server error']);
            }
        } else {
            return ResponseService::apiResponse(200, 'Order Id Not Found', ['status' => false]);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RefundRequest  $refundRequest
     * @return \Illuminate\Http\Response
     */
    public function show(RefundRequest $refunds, $id)
    {
        $refundInfo = RefundRequest::with('order')->find($id);
        return view('admin.refunds.show', compact('refundInfo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RefundRequest  $refundRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(RefundRequest $refundRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RefundRequest  $refundRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RefundRequest $refundRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RefundRequest  $refundRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(RefundRequest $refundRequest)
    {
        //
    }
}
