<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTradeSlTpRequest;
use App\Http\Requests\StoreTradeSlTpRequest;
use App\Http\Requests\UpdateTradeSlTpRequest;
use App\Models\Trade;
use App\Models\TradeSlTp;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TradeSlTpController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('trade_sl_tp_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = TradeSlTp::with(['trade'])->select(sprintf('%s.*', (new TradeSlTp())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'trade_sl_tp_show';
                $editGate = 'trade_sl_tp_edit';
                $deleteGate = 'trade_sl_tp_delete';
                $crudRoutePart = 'trade-sl-tps';

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
            $table->addColumn('trade.ticket', function ($row) {
                return $row->trade ? $row->trade->ticket : '';
            });

            $table->editColumn('type', function ($row) {
                return  $row->type == 2 ? 'Tp' : 'SL';
            });
            $table->editColumn('value', function ($row) {
                return $row->value ? $row->value : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'trade','trade.ticket']);

            return $table->make(true);
        }

        $trades = Trade::all()->pluck('ticket','id');

        return view('admin.tradeSlTps.index', compact('trades'));
    }

    public function create()
    {
        abort_if(Gate::denies('trade_sl_tp_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $trades = Trade::pluck('account', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.tradeSlTps.create', compact('trades'));
    }

    public function store(StoreTradeSlTpRequest $request)
    {
        $tradeSlTp = TradeSlTp::create($request->all());

        return redirect()->route('admin.trade-sl-tps.index');
    }

    public function edit(TradeSlTp $tradeSlTp)
    {
        abort_if(Gate::denies('trade_sl_tp_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $trades = Trade::pluck('account', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tradeSlTp->load('trade');

        return view('admin.tradeSlTps.edit', compact('tradeSlTp', 'trades'));
    }

    public function update(UpdateTradeSlTpRequest $request, TradeSlTp $tradeSlTp)
    {
        $tradeSlTp->update($request->all());

        return redirect()->route('admin.trade-sl-tps.index');
    }

    public function show(TradeSlTp $tradeSlTp)
    {
        abort_if(Gate::denies('trade_sl_tp_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tradeSlTp->load('trade');

        return view('admin.tradeSlTps.show', compact('tradeSlTp'));
    }

    public function destroy(TradeSlTp $tradeSlTp)
    {
        abort_if(Gate::denies('trade_sl_tp_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tradeSlTp->delete();

        return back();
    }

    public function massDestroy(MassDestroyTradeSlTpRequest $request)
    {
        TradeSlTp::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
