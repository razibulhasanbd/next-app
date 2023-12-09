<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\BreachEvent;
use Gate;
use Illuminate\Http\Request;
use PhpParser\JsonDecoder;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class BreachEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function breachEvent(Request $request)
    {

        if ($request->ajax()) {

            // $query = BreachEvent::with(['account.customer'])->select(sprintf('%s*', (new BreachEvent())->table));
            $query = BreachEvent::with(['account','account.customer'])->select(sprintf('%s.*', (new BreachEvent())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'breachEvent_show';
                $editGate = 'breachEvent_edit';
               $deleteGate = 'breachEvent_delete';
               $crudRoutePart = 'accounts.breachEvent';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });
            $table->editColumn('id', function ($row) {
                return  $row->id ? '<a href=' . route('admin.accounts.breachEvent.show', $row->id) . '>' . $row->id . '</a>' : '';
            });

            $table->editColumn('account.customer.name', function ($row) {
                return $row->account->customer ? (is_string($row->account->customer) ? $row->account->customer : $row->account->customer->name) : '';
            });

            $table->editColumn('account.customer.email', function ($row) {
                return $row->account->customer ? (is_string($row->account->customer) ? $row->account->customer : $row->account->customer->email) : '';

            });

            $table->editColumn('login', function ($row) {
                return $row->login ? $row->login : '';
            });
            $table->editColumn('account.breachedby', function ($row) {
                return $row->account ? $row->account->breachedby : '';
            });

            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? frontEndTimeConverterView($row->created_at) : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'account','account.breachedby', 'account.customer.email', 'account.customer.name', 'id', 'breached_by']);

            return $table->make(true);
        }

        return view('admin.accounts.breachevent');
    }



    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BreachEvent  $breachEvent
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $breachEventIdDetails = BreachEvent::find($id);
        $metrics = json_decode($breachEventIdDetails->metrics);
        $trade = json_decode($breachEventIdDetails->trades);

        return view('admin.accounts.breachEventDetails', compact('metrics', 'trade', 'breachEventIdDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BreachEvent  $breachEvent
     * @return \Illuminate\Http\Response
     */
    public function edit(BreachEvent $breachEvent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BreachEvent  $breachEvent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BreachEvent $breachEvent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BreachEvent  $breachEvent
     * @return \Illuminate\Http\Response
     */
    public function destroy(BreachEvent $breachEvent)
    {
        //
    }
}
