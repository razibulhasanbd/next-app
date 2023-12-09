<?php

namespace App\Http\Controllers\Admin;
use Exception;
use App\Models\Orders;
use Illuminate\Http\Request;
use App\Models\AddExtraCharge;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\StoreAddCharge;
use App\Services\AddChargeService;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
class AddExtraChargeController extends Controller
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
            $query = AddExtraCharge::with('user', 'order')->select(sprintf('%s.*', (new AddExtraCharge())->table));
            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'order_show';
                $editGate = '';
                $deleteGate = '';
                $crudRoutePart = 'charges';

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
                return $row->user ? '<a href=' . route('admin.users.show', $row->user->id) . ' target=_blank>' . $row->user->name . '</a>' : '';
            });
            $table->editColumn('order_id', function ($row) {
                return $row->order_id ?? $row->order_id;
            });
            $table->editColumn('amount', function ($row) {
                return $row->amount ? $row->amount : '';
            });
            $table->editColumn('remarks', function ($row) {
                return $row->remarks ? $row->remarks : 0;
            });
            
            $table->rawColumns(['actions','placeholder','order.transaction_id', 'user.name', 'order_id', 'amount', 'remarks']);

            return $table->make(true);
        }

        return view('admin.addCharges.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $order = Orders::find($id);
        return view('admin.addCharges.create', compact('order'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function callAddChargeApi(StoreAddCharge $request)
    { 
        $orderInfo = Orders::find($request->order_id);
        try {
            $response = Http::withHeaders([
                'Accept'            => 'application/json',
                'communication-key' => config('ph-config.COMMUNICATION_KEY'),
            ])->post(config('ph-config.PH_URL') . "/add-extra-charge", [
                "transaction_id" => $orderInfo->transaction_id,
                "amount"         => $request->amount,
                "remarks"        => $request->comment,
            ]);
        
            if ($response['code'] == 200) {
                (new AddChargeService)->storeExtraChargeAmount(
                    $orderInfo->id,
                    $response['data']['storeExtraCharge']['amount'],
                    $response['data']['storeExtraCharge']['remarks']
                );
                return redirect()->route('admin.charges.index')->with('message', 'Extra Amount Store Successfully');
            } else {
                return back()->with('warning', $response['message']);
            }
        } catch (\Exception $exception) {
            Log::error($exception);
            return response()->json(['status' => false, 'data' => [], 'message' => 'Internal server error']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AddExtraCharge  $addExtraCharge
     * @return \Illuminate\Http\Response
     */
    public function show(AddExtraCharge $addExtraCharge)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AddExtraCharge  $addExtraCharge
     * @return \Illuminate\Http\Response
     */
    public function edit(AddExtraCharge $addExtraCharge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AddExtraCharge  $addExtraCharge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AddExtraCharge $addExtraCharge)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AddExtraCharge  $addExtraCharge
     * @return \Illuminate\Http\Response
     */
    public function destroy(AddExtraCharge $addExtraCharge)
    {
        //
    }
}
