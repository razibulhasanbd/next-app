<?php

namespace App\Http\Controllers\Admin;

use Gate;
use DateTime;
use Carbon\Carbon;
use App\Models\Trade;
use App\Models\Account;
use App\Models\Package;
use Carbon\CarbonInterval;
use Illuminate\Foundation\Mix;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ArbitraryTrade;
use App\Exports\TradeFilterExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTradeRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\UpdateTradeRequest;
use App\Http\Requests\MassDestroyTradeRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\CsvImportTrait;

class TradeController extends Controller
{
    use CsvImportTrait;




    public function index(Request $request)
    {

        abort_if(Gate::denies('trade_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Trade::with('account','tradeSlFirst','tradeTPFirst')->where('created_at','>=','2022-06-01')->select(sprintf('%s.*', (new Trade())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'trade_show';
                $editGate = 'trade_edit';
                $deleteGate = 'trade_delete';
                $crudRoutePart = 'trades';

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
            $table->editColumn('account.login', function ($row) {
                return $row->account ? (is_string($row->account) ? $row->account : $row->account->login) : '';
            });
            $table->editColumn('login', function ($row) {
                return $row->login ? $row->login : '';
            });
            $table->editColumn('close_price', function ($row) {
                return $row->close_price ? round($row->close_price, 5) : '';
            });
            $table->editColumn('close_time', function ($row) {
                return $row->close_time ? $row->close_time : '';
            });
            $table->editColumn('close_time_str', function ($row) {
                return $row->close_time_str ? frontEndTimeConverterView($row->close_time_str) : '';
            });
            $table->editColumn('commission', function ($row) {
                return $row->commission ? round($row->commission, 5) : '';
            });
            $table->editColumn('digits', function ($row) {
                return $row->digits ? $row->digits : '';
            });
            $table->editColumn('duration', function ($row) {


                if ($row->close_time == 0) return 'running';
                $time = round($row->close_time - $row->open_time);
                $duration = sprintf('%02d:%02d:%02d', ($time / 3600), ($time / 60 % 60), $time % 60);

                return $duration;
            });
            $table->editColumn('lots', function ($row) {
                return $row->volume ? round($row->volume / 100, 5)  : '';
                // return $row->lots ? round($row->lots, 5) : '';
            });
            $table->editColumn('open_price', function ($row) {
                return $row->open_price ? round($row->open_price, 5) : '';
            });
            $table->editColumn('open_time', function ($row) {
                return $row->open_time ? $row->open_time : '';
            });
            $table->editColumn('open_time_str', function ($row) {
                return $row->open_time_str ? frontEndTimeConverterView($row->open_time_str) : '';
            });
            $table->editColumn('pips', function ($row) {
                return $row->pips ? round($row->pips, 5) : '';
            });
            $table->editColumn('profit', function ($row) {
                return $row->profit ? round($row->profit, 5) : '';
            });
            $table->editColumn('reason', function ($row) {
                $reasonTitle = $this->checkReason($row->reason);
                return $reasonTitle ? $reasonTitle : '';
            });
            $table->editColumn('sl', function ($row) {
                return $row->sl ? round($row->sl, 5) : '';
            });
            $table->editColumn('tradeSlFirst', function ($row) {
                 return $row->tradeSlFirst ? $row->tradeSlFirst->value : '';
            });
            $table->editColumn('state', function ($row) {
                return $row->state ? $row->state : '';
            });
            $table->editColumn('swap', function ($row) {
                return $row->swap ? $row->swap : '';
            });
            $table->editColumn('symbol', function ($row) {
                return $row->symbol ? $row->symbol : '';
            });
            $table->editColumn('ticket', function ($row) {
                return $row->ticket ? $row->ticket : '';
            });
            $table->editColumn('tp', function ($row) {
                return $row->tp ? round($row->tp, 5) : '';
            });
            $table->editColumn('tradeTPFirst', function ($row) {
                return $row->tradeTPFirst ? $row->tradeTPFirst->value : '';
            });
            $table->editColumn('type', function ($row) {
                return $row->type ? $row->type : '';
            });
            $table->editColumn('type_str', function ($row) {
                return $row->type_str ? $row->type_str : '';
            });
            $table->editColumn('volume', function ($row) {
                return $row->volume ? $row->volume : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'account','tradeTPFirst','tradeSlFirst']);

            return $table->make(true);
        }

        return view('admin.trades.index');
    }


    public function checkReason($reason)
    {

        $reasonTitle = '';
        switch ($reason) {
            case 0:
                $reasonTitle = 'client terminal';
                return $reasonTitle;
            case 1:
                $reasonTitle = 'expert';
                return $reasonTitle;
            case 2:
                $reasonTitle = 'dealer';
                return $reasonTitle;
            case 3:
                $reasonTitle = 'signal';
                return $reasonTitle;
            case 4:
                $reasonTitle = 'gateway';
                return $reasonTitle;
            case 5:
                $reasonTitle = 'mobile terminal';
                return $reasonTitle;
            case 6:
                $reasonTitle = 'Web terminal';
                return $reasonTitle;
            case 7:
                $reasonTitle = 'API';
                return $reasonTitle;
            default:
                return $reasonTitle;
        }
    }


    public function create()
    {
        abort_if(Gate::denies('trade_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $accounts = Account::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        return view('admin.trades.create', compact('accounts'));
    }

    public function store(StoreTradeRequest $request)
    {
        $trade = Trade::create($request->all());

        return redirect()->route('admin.trades.index');
    }

    public function edit(Trade $trade)
    {
        abort_if(Gate::denies('trade_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.trades.edit', compact('trade'));
    }

    public function update(UpdateTradeRequest $request, Trade $trade)
    {
        $trade->update($request->all());

        return redirect()->route('admin.trades.index');
    }

    public function show(Trade $trade)
    {

        abort_if(Gate::denies('trade_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.trades.show', compact('trade'));
    }

    public function showTradeTicket($ticket)
    {
        $trade = Trade::whereTicket($ticket)->first();
        abort_if(Gate::denies('trade_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.trades.show', compact('trade'));
    }

    public function destroy(Trade $trade)
    {
        abort_if(Gate::denies('trade_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $trade->delete();

        return back();
    }

    public function massDestroy(MassDestroyTradeRequest $request)
    {
        Trade::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function openTrade(Request $request)
    {
        abort_if(Gate::denies('trade_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Trade::with('account.plan.package')->where('close_time', 0)->select(sprintf('%s.*', (new Trade())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'trade_show';
                $editGate = 'trade_edit';
                $deleteGate = 'trade_delete';
                $crudRoutePart = 'trades';

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

            $table->editColumn('account.plan.package.name', function ($row) {
                return $row->account->plan->package->name ?: '';
            });
            $table->editColumn('login', function ($row) {
                return $row->login ? $row->login : '';
            });
            $table->editColumn('close_price', function ($row) {
                return $row->close_price ? $row->close_price : '';
            });
            $table->editColumn('close_time', function ($row) {
                return $row->close_time ? $row->close_time : '';
            });
            $table->editColumn('close_time_str', function ($row) {
                return $row->close_time_str ? frontEndTimeConverterView($row->close_time_str) : '';
            });
            $table->editColumn('commission', function ($row) {
                return $row->commission ? $row->commission : '';
            });
            $table->editColumn('digits', function ($row) {
                return $row->digits ? $row->digits : '';
            });

            $table->editColumn('lots', function ($row) {
                return $row->lots ? $row->lots : '';
            });
            $table->editColumn('open_price', function ($row) {
                return $row->open_price ? $row->open_price : '';
            });
            $table->editColumn('open_time', function ($row) {
                return $row->open_time ? $row->open_time : '';
            });
            $table->editColumn('open_time_str', function ($row) {
                return $row->open_time_str ? frontEndTimeConverterView($row->open_time_str) : '';
            });
            $table->editColumn('pips', function ($row) {
                return $row->pips ? $row->pips : '';
            });
            $table->editColumn('profit', function ($row) {
                return $row->profit ? $row->profit : '';
            });
            $table->editColumn('reason', function ($row) {
                $reasonTitle = $this->checkReason($row->reason);
                return $reasonTitle ? $reasonTitle : '';
            });
            $table->editColumn('sl', function ($row) {
                return $row->sl ? $row->sl : '';
            });
            $table->editColumn('state', function ($row) {
                return $row->state ? $row->state : '';
            });
            $table->editColumn('swap', function ($row) {
                return $row->swap ? $row->swap : '';
            });
            $table->editColumn('symbol', function ($row) {
                return $row->symbol ? $row->symbol : '';
            });
            $table->editColumn('ticket', function ($row) {
                return $row->ticket ? $row->ticket : '';
            });
            $table->editColumn('tp', function ($row) {
                return $row->tp ? $row->tp : '';
            });
            $table->editColumn('type', function ($row) {
                return $row->type ? $row->type : '';
            });
            $table->editColumn('type_str', function ($row) {
                return $row->type_str ? $row->type_str : '';
            });
            $table->editColumn('volume', function ($row) {
                return $row->volume ? $row->volume : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'account.login', 'account.plan.package.name']);

            return $table->make(true);
        }

        $getAllPackage = Package::get();
        return view('admin.trades.openTrade', compact('getAllPackage'));
    }

    public function closeTrade(Request $request)
    {
        abort_if(Gate::denies('trade_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Trade::with('account')->where('close_time', '!=', 0)->select(sprintf('%s.*', (new Trade())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'trade_show';
                $editGate = 'trade_edit';
                $deleteGate = 'trade_delete';
                $crudRoutePart = 'trades';

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
            $table->editColumn('account.id', function ($row) {
                return $row->account ? (is_string($row->account) ? $row->account->id : $row->account->id) : '';
            });
            $table->editColumn('login', function ($row) {
                return $row->login ? $row->login : '';
            });
            $table->editColumn('close_price', function ($row) {
                return $row->close_price ? $row->close_price : '';
            });
            $table->editColumn('close_time', function ($row) {
                return $row->close_time ? $row->close_time : '';
            });
            $table->editColumn('close_time_str', function ($row) {
                return $row->close_time_str ? frontEndTimeConverterView($row->close_time_str) : '';
            });
            $table->editColumn('commission', function ($row) {
                return $row->commission ? $row->commission : '';
            });
            $table->editColumn('digits', function ($row) {
                return $row->digits ? $row->digits : '';
            });

            $table->editColumn('lots', function ($row) {
                return $row->lots ? $row->lots : '';
            });
            $table->editColumn('open_price', function ($row) {
                return $row->open_price ? $row->open_price : '';
            });
            $table->editColumn('open_time', function ($row) {
                return $row->open_time ? $row->open_time : '';
            });
            $table->editColumn('open_time_str', function ($row) {
                return $row->open_time_str ? frontEndTimeConverterView($row->open_time_str) : '';
            });
            $table->editColumn('pips', function ($row) {
                return $row->pips ? $row->pips : '';
            });
            $table->editColumn('profit', function ($row) {
                return $row->profit ? $row->profit : '';
            });
            $table->editColumn('reason', function ($row) {
                $reasonTitle = $this->checkReason($row->reason);
                return $reasonTitle ? $reasonTitle : '';
            });
            $table->editColumn('sl', function ($row) {
                return $row->sl ? $row->sl : '';
            });
            $table->editColumn('state', function ($row) {
                return $row->state ? $row->state : '';
            });
            $table->editColumn('swap', function ($row) {
                return $row->swap ? $row->swap : '';
            });
            $table->editColumn('symbol', function ($row) {
                return $row->symbol ? $row->symbol : '';
            });
            $table->editColumn('ticket', function ($row) {
                return $row->ticket ? $row->ticket : '';
            });
            $table->editColumn('tp', function ($row) {
                return $row->tp ? $row->tp : '';
            });
            $table->editColumn('type', function ($row) {
                return $row->type ? $row->type : '';
            });
            $table->editColumn('type_str', function ($row) {
                return $row->type_str ? $row->type_str : '';
            });
            $table->editColumn('volume', function ($row) {
                return $row->volume ? $row->volume : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'account']);

            return $table->make(true);
        }

        return view('admin.trades.closeTrade');
    }

    public function arbitraryTrade(Request $request)
    {

        abort_if(Gate::denies('trade_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {

            $query = ArbitraryTrade::query()->select(sprintf('%s.*', (new ArbitraryTrade())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'trade_show';
                $editGate = 'trade_edit';
                $deleteGate = 'trade_delete';
                $crudRoutePart = 'trades';

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

            $table->editColumn('account_id', function ($row) {
                return $row->account_id ? $row->account_id : '';
            });
            $table->editColumn('customer_name', function ($row) {
                $account = Account::with('customer')->find($row->account_id);
                return $row->account_id ? $account->customer->name : '';
            });

            $table->editColumn('login', function ($row) {
                return $row->login ? '<a href=' . route('admin.accounts.show', $row->account_id) . '>' . $row->login . '</a>' : '';
                //return $row->login ? $row->login : '';
            });

            $table->editColumn('ticket', function ($row) {
                return $row->ticket ? '<a href=' . route('admin.trades.showTicket', $row->ticket) . '>' . $row->ticket . '</a>' : '';
                //return $row->ticket ? $row->ticket : '';
            });

            $table->editColumn('time_difference', function ($row) {
                return $row->time_difference ? $row->time_difference : '';
            });
            $table->addColumn('count', function ($row) {
                $countArbitrary = ArbitraryTrade::where('account_id', $row->account_id)->count();
                return $countArbitrary;
            });

            $table->rawColumns(['actions', 'count', 'placeholder', 'account', 'login', 'ticket', 'customer_name']);

            return $table->make(true);
        }

        return view('admin.trades.arbitraryTrade');
    }

    public function arbitraryTradeReport(Request $request)
    {
        abort_if(Gate::denies('trade_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');



        $startDate = Carbon::yesterday();

        $endDate = Carbon::now();

        if ($request->ajax()) {


            $query = DB::table('arbitrary_trades')
                ->select(DB::raw('account_id, login, count(login) as `trade_count`,MAX(created_at) as `last_trade`'))->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->groupBy('login', 'account_id')->get();

            if (isset($request->startDate) && isset($request->endDate)) {

                $query = DB::table('arbitrary_trades')
                    ->select(DB::raw('account_id, login, count(login) as `trade_count`,MAX(created_at) as `last_trade`'))->where('created_at', '>=', $request->startDate)->where('created_at', '<=', $request->endDate)->groupBy('login', 'account_id')->get();
            }


            if ($request->endDate == '' && $request->startDate != '') {

                $query = DB::table('arbitrary_trades')
                    ->select(DB::raw('account_id, login, count(login) as `trade_count`,MAX(created_at) as `last_trade`'))->where('created_at', '>=', carbon::createFromFormat('Y-m-d H:i:s', $request->startDate)->format('Y-m-d') . ' 00:00:00')->where('created_at', '<=', carbon::createFromFormat('Y-m-d H:i:s', $request->startDate)->format('Y-m-d') . ' 23:59:59')->groupBy('login', 'account_id')->get();
            }
            if ($request->startDate == '' && $request->endDate != '') {
                $query = DB::table('arbitrary_trades')
                    ->select(DB::raw('account_id, login, count(login) as `trade_count`,MAX(created_at) as `last_trade`'))->where('created_at', '>=', carbon::createFromFormat('Y-m-d H:i:s', $request->endDate)->format('Y-m-d') . ' 00:00:00')->where('created_at', '<=', carbon::createFromFormat('Y-m-d H:i:s', $request->endDate)->format('Y-m-d') . ' 23:59:59')->groupBy('login', 'account_id')->get();
            }



            $table = Datatables::of($query);

            $table->editColumn('account_id', function ($row) {
                return  $row->account_id ? $row->account_id : '';
            });
            $table->editColumn('login', function ($row) {
                return  $row->login ? '<a href=' . route('admin.accounts.show', $row->account_id) . '>' . $row->login . '</a>' : '';
            });
            $table->editColumn('trade_count', function ($row) {
                return $row->trade_count ? $row->trade_count : '';
            });
            $table->editColumn('last_trade', function ($row) {
                return  $row->last_trade ?  $row->last_trade : '';
            });

            $table->rawColumns(['account_id', 'login', 'trade_count', 'last_trade']);
            return $table->make(true);
        }

        return view('admin.trades.arbitraryTradeReport');
    }

    public function EATrades(Request $request)
    {
        abort_if(Gate::denies('trade_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {

            $query = '';
            if (isset($request->profitFilter)) {
                $query = DB::select('SELECT customers.name, customers.email ,accounts.login,plans.title,trades.reason,COUNT(*) as tradeCount FROM trades
            INNER JOIN accounts ON accounts.login=trades.login
            INNER JOIN plans ON accounts.plan_id= plans.id
            INNER JOIN customers ON accounts.customer_id=customers.id
            WHERE reason=1 AND accounts.balance>plans.startingBalance and accounts.breachedby LIKE  "%Profit%" GROUP BY accounts.login,plans.title,trades.reason , customers.email , customers.name');
            } else {
                $query = DB::select('SELECT customers.name, customers.email ,accounts.login,plans.title,trades.reason,COUNT(*) as tradeCount FROM trades
            INNER JOIN accounts ON accounts.login=trades.login
            INNER JOIN plans ON accounts.plan_id= plans.id
            INNER JOIN customers ON accounts.customer_id=customers.id
            WHERE reason=1 AND accounts.balance>plans.startingBalance GROUP BY accounts.login,plans.title,trades.reason , customers.email , customers.name');
            }


            $table = Datatables::of($query);

            $table->editColumn('customers.name', function ($row) {
                return  $row->name ? $row->name : '';
            });
            $table->editColumn('customers.email', function ($row) {
                return  $row->email ? $row->email : '';
            });
            $table->editColumn('accounts.login', function ($row) {
                return  $row->login ? $row->login : '';
            });
            $table->editColumn('plans.title', function ($row) {
                return  $row->title ? $row->title : '';
            });

            $table->editColumn('trades.reason', function ($row) {
                return $row->reason ? $row->reason : '';
            });
            $table->editColumn('tradeCount', function ($row) {
                return  $row->tradeCount ?  $row->tradeCount : '';
            });

            $table->rawColumns(['customers.name', 'customers.email', 'accounts.login','plans.title', 'trades.reason', 'tradeCount']);
            return $table->make(true);
        }


        return view('admin.trades.eaTrades');
    }

    public function tradesByDateRange(Request $request){

        abort_if(Gate::denies('trade_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {

            $startDate= str_replace('-', '.', $request->startDate);
            $endDate= str_replace('-', '.', $request->endDate);

            $query = Trade::with('account','tradeSlFirst','tradeTPFirst')->whereBetween('trades.open_time_str', [$startDate, $endDate])->select(sprintf('%s.*', (new Trade())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'trade_show';
                $editGate = 'trade_edit';
                $deleteGate = 'trade_delete';
                $crudRoutePart = 'trades';

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
            $table->editColumn('account.login', function ($row) {
                return $row->account ? (is_string($row->account) ? $row->account : $row->account->login) : '';
            });
            $table->editColumn('login', function ($row) {
                return $row->login ? $row->login : '';
            });
            $table->editColumn('close_price', function ($row) {
                return $row->close_price ? round($row->close_price, 5) : '';
            });
            $table->editColumn('close_time', function ($row) {
                return $row->close_time ? $row->close_time : '';
            });
            $table->editColumn('close_time_str', function ($row) {
                return $row->close_time_str ? frontEndTimeConverterView($row->close_time_str) : '';
            });
            $table->editColumn('commission', function ($row) {
                return $row->commission ? round($row->commission, 5) : '';
            });
            $table->editColumn('digits', function ($row) {
                return $row->digits ? $row->digits : '';
            });
            $table->editColumn('duration', function ($row) {


                if ($row->close_time == 0) return 'running';
                $time = round($row->close_time - $row->open_time);
                $duration = sprintf('%02d:%02d:%02d', ($time / 3600), ($time / 60 % 60), $time % 60);

                return $duration;
            });
            $table->editColumn('lots', function ($row) {
                return $row->volume ? round($row->volume / 100, 5)  : '';
                // return $row->lots ? round($row->lots, 5) : '';
            });
            $table->editColumn('open_price', function ($row) {
                return $row->open_price ? round($row->open_price, 5) : '';
            });
            $table->editColumn('open_time', function ($row) {
                return $row->open_time ? $row->open_time : '';
            });
            $table->editColumn('open_time_str', function ($row) {
                return $row->open_time_str ? frontEndTimeConverterView($row->open_time_str) : '';
            });
            $table->editColumn('pips', function ($row) {
                return $row->pips ? round($row->pips, 5) : '';
            });
            $table->editColumn('profit', function ($row) {
                return $row->profit ? round($row->profit, 5) : '';
            });
            $table->editColumn('reason', function ($row) {
                $reasonTitle = $this->checkReason($row->reason);
                return $reasonTitle ? $reasonTitle : '';
            });
            $table->editColumn('sl', function ($row) {
                return $row->sl ? round($row->sl, 5) : '';
            });
            $table->editColumn('tradeSlFirst', function ($row) {
                 return $row->tradeSlFirst ? $row->tradeSlFirst->value : '';
            });
            $table->editColumn('state', function ($row) {
                return $row->state ? $row->state : '';
            });
            $table->editColumn('swap', function ($row) {
                return $row->swap ? $row->swap : '';
            });
            $table->editColumn('symbol', function ($row) {
                return $row->symbol ? $row->symbol : '';
            });
            $table->editColumn('ticket', function ($row) {
                return $row->ticket ? $row->ticket : '';
            });
            $table->editColumn('tp', function ($row) {
                return $row->tp ? round($row->tp, 5) : '';
            });
            $table->editColumn('tradeTPFirst', function ($row) {
                return $row->tradeTPFirst ? $row->tradeTPFirst->value : '';
            });
            $table->editColumn('type', function ($row) {
                return $row->type ? $row->type : '';
            });
            $table->editColumn('type_str', function ($row) {
                return $row->type_str ? $row->type_str : '';
            });
            $table->editColumn('volume', function ($row) {
                return $row->volume ? $row->volume : '';
            });

            $table->rawColumns(['actions', 'placeholder','account.login', 'account','tradeTPFirst','tradeSlFirst']);

            return $table->make(true);
        }

        return view('admin.trades.tradesByDateRange');

    }



    public function tradesFilterCal(Request $request)
    {
        abort_if(Gate::denies('trade_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $startDate = str_replace('-', '.', $request->startDate);
            $endDate   = str_replace('-', '.', $request->endDate);

            $topSixPair = DB::table('trades')
            ->select('symbol', DB::raw('SUM(volume/100) AS lot_size'))
            ->whereBetween('trades.open_time_str', [$startDate, $endDate])
            ->groupBy('symbol')
            ->orderBy('lot_size','desc')
            ->limit(10)
            ->get()->toArray();

            $topSixPair = $this->additionalSymbolGroup($topSixPair);
            $totalLotTrades = DB::table('trades')
            ->whereBetween('trades.open_time_str', [$startDate, $endDate])
            ->sum('volume');

            $view = view('admin.trades.partialTable', compact(['topSixPair','totalLotTrades']))->render();
            return response(['status' => 200, 'view' => $view]);
    }

    public function ConsistencyReportByDate(Request $request)
    {



        $filterStartDate =  Carbon::createFromFormat('m/d/Y',$request->startDate)->timestamp;
        // $filterStartDate = Carbon::parse($request->startDate)->timestamp;
        $filterendDate = date('Y-m-d H:i:s', strtotime($request->endDate . '23:59:59'));

        $account = Account::find($request->accountId);
        $controller = new \App\Http\Controllers\AccountController();
        $allPlanRule = $controller->accountPlanRules($account->id);
        $deviation = $allPlanRule['CRD']['value'] ?? '2.5';

        if ($account != null) {

            $lastFriday = Carbon::createFromTimeStamp(strtotime("last Friday", $filterStartDate))->toDateString();

            $checkSubscription = $account->latestSubscription; // get the lastest subs
            $subsStart = $checkSubscription->created_at;

            $joinDateDiff = (new DateTime($subsStart))->diff(Carbon::createFromTimeStamp(strtotime("last Friday", $filterStartDate)))->days;

            //Get All Trade last Friday and subscription wise
            $thisweekTrades = Trade::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(volume) as lots'), DB::raw('count(volume) as trade_count'))
                ->whereAccountId($account->id)
                ->where('created_at', '>=', $checkSubscription->created_at)
                ->whereDate('created_at', '>', $lastFriday)
                ->whereDate('created_at', '<', $filterendDate)
                ->whereIn('type', array(0, 1))
                ->groupBy('date')
                ->get();

            $overallTrades = collect();
            if ($joinDateDiff >= 1) {
                $overallTrades = Trade::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(volume) as lots'), DB::raw('count(volume) as trade_count'))
                    ->whereAccountId($account->id)
                    ->whereDate('created_at', '<=', $lastFriday)
                    ->where('created_at', '>=', $checkSubscription->created_at)
                    ->whereIn('type', array(0, 1))
                    ->groupBy('date')
                    ->get();
                //Get All Trade last Friday and subscription wise
                $thisweekTrades = Trade::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(volume) as lots'), DB::raw('count(volume) as trade_count'))
                    ->whereAccountId($account->id)
                    ->whereIn('type', array(0, 1))
                    ->where('created_at', '>=', $checkSubscription->created_at)
                    ->whereDate('created_at', '>', $lastFriday)
                    ->whereDate('created_at', '<', $filterendDate)
                    ->groupBy('date')
                    ->get();
            }

            if (!($thisweekTrades->isEmpty())) {
                $thisWeekTotalLots = $thisweekTrades->sum("lots") / 100;
                $thisWeekTotaltrades = $thisweekTrades->sum("trade_count");
                $thisWeekActiveTradingDay = $thisweekTrades->count();

                $thisweekTrades = [

                    "totalLots" => $thisWeekTotalLots,
                    "totaltrades" => $thisWeekTotaltrades,
                    "activeTradingDay" => $thisWeekActiveTradingDay,

                    'avTrade' => $thisWeekTotaltrades / ($thisWeekActiveTradingDay == 0 ? 1 : $thisWeekActiveTradingDay),
                    'avLot' => $thisWeekTotalLots / ($thisWeekActiveTradingDay == 0 ? 1 : $thisWeekActiveTradingDay),

                ];
            } else {
                $thisweekTrades = false;
            }

            if (!($overallTrades->isEmpty())) {

                $overallTotalLots = $overallTrades->sum("lots") / 100;
                $overallTotaltrades = $overallTrades->sum("trade_count");
                $overallActiveTradingDay = $overallTrades->count();

                $overallAvgTotalLots = round($overallTotalLots / $overallActiveTradingDay, 2);
                $overallAvgTotaltrades = round($overallTotaltrades / $overallActiveTradingDay, 2);
                //$multiple = ($joinDateDiff >= 2) ? 1 : (5 / $overallActiveTradingDay);

                $overallTrades = [
                    "debug" => [
                        "lastFriday" => $lastFriday,
                        // "checkSubscription" => $checkSubscription
                    ],
                    "totalTradesCount" => $overallTotaltrades,
                    "totalAvgLots" => $overallAvgTotalLots,
                    "totalAvgtrades" => $overallAvgTotaltrades,
                    "activeTradingDay" => $overallActiveTradingDay,
                    "lots_upper_limit" => upperLimit($overallTotalLots, $overallActiveTradingDay, $deviation),
                    "lots_lower_limit" => lowerLimit($overallTotalLots, $overallActiveTradingDay, $deviation),
                    "trades_upper_limit" => upperLimit($overallTotaltrades, $overallActiveTradingDay, $deviation),
                    "trades_lower_limit" => lowerLimit($overallTotaltrades, $overallActiveTradingDay, $deviation),
                ];
            } else {
                $overallTrades = false;
            }



            $trade_weekly_average = $thisweekTrades["avTrade"] ?? null;
            $trade_overall_average = $overallAvgTotaltrades ?? null;
            $trade_low = $overallTrades["trades_lower_limit"] ?? null;
            $trade_high = $overallTrades["trades_upper_limit"] ?? null;

            $lot_weekly_average = $thisweekTrades["avLot"] ?? null;
            $lot_overall_average = $overallAvgTotalLots ?? null;
            $lot_low = $overallTrades["lots_lower_limit"] ?? null;
            $lot_high = $overallTrades["lots_upper_limit"] ?? null;

            $view = view('admin.accounts.consistencyReportPartial', compact('trade_weekly_average', 'trade_overall_average', 'trade_low', 'trade_high', 'lot_weekly_average', 'lot_overall_average', 'lot_low', 'lot_high', 'deviation'))->render();
            return response(['status' => 200, 'view' => $view]);

            // return $result = [

            //     "trade" => [
            //         "weekly_average" => $thisweekTrades["avTrade"] ?? null,
            //         "overall_average" => $overallAvgTotaltrades ?? null,
            //         "low" => $overallTrades["trades_lower_limit"] ?? null,
            //         "high" => $overallTrades["trades_upper_limit"] ?? null,
            //     ],
            //     "lot" => [
            //         "weekly_average" => $thisweekTrades["avLot"] ?? null,
            //         "overall_average" => $overallAvgTotalLots ?? null,
            //         "low" => $overallTrades["lots_lower_limit"] ?? null,
            //         "high" => $overallTrades["lots_upper_limit"] ?? null,
            //     ],
            //     "allInfo" => $overallTrades,

            // ];
        } else {
            return response()->json(['message' => 'Account Id Not valid'], 404);
        }
    }


    /** customize array count symbol wise slot_size
     * @param $topSixPair
     * @return mixed
     */
    public function additionalSymbolGroup($topSixPair):?array
    {
        $arrays = array_reduce($topSixPair, function ($output, $item) {
            $symbol = str_replace('.i', '', $item->symbol);
            if (!isset($output[$symbol])) {
                $output[$symbol] = ['symbol' => $symbol, 'lot_size' => $item->lot_size];
            } else {
                $output[$symbol]['lot_size'] += $item->lot_size;
            }
            return $output;
        });
        return  $arrays ? array_slice($arrays, 0, 5) : null;
    }
}

function upperLimit(float $int, int $day, $deviation)
{

    return round($int / $day * $deviation, 2);
}
function lowerLimit(float $int, int $day, $deviation)
{
    return round($int / $day / $deviation, 2);
}
