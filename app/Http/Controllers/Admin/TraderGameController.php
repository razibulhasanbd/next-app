<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTraderGameRequest;
use App\Http\Requests\StoreTraderGameRequest;
use App\Http\Requests\UpdateTraderGameRequest;
use App\Models\TraderGame;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TraderGameController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('trader_game_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = TraderGame::query()->select(sprintf('%s.*', (new TraderGame())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'trader_game_show';
                $editGate = 'trader_game_edit';
                $deleteGate = 'trader_game_delete';
                $crudRoutePart = 'trader-games';

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
            $table->editColumn('date', function ($row) {
                return $row->date ? frontEndTimeConverterView($row->date, 'date') : '';
            });
            $table->editColumn('dashboard_user', function ($row) {
                return $row->dashboard_user ? $row->dashboard_user : '';
            });

            $table->editColumn('dashboard_email', function ($row) {
                return $row->dashboard_email ? $row->dashboard_email : '';
            });
            $table->editColumn('pnl', function ($row) {
                return $row->pnl ? $row->pnl : '';
            });
            $table->editColumn('mental_score', function ($row) {
                return $row->mental_score ? $row->mental_score : '';
            });
            $table->editColumn('tactical_score', function ($row) {
                return $row->tactical_score ? $row->tactical_score : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.traderGames.index');
    }

    public function create()
    {
        abort_if(Gate::denies('trader_game_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.traderGames.create');
    }

    public function store(StoreTraderGameRequest $request)
    {
        $traderGame = TraderGame::create($request->all());

        return redirect()->route('admin.trader-games.index');
    }

    public function edit(TraderGame $traderGame)
    {
        abort_if(Gate::denies('trader_game_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.traderGames.edit', compact('traderGame'));
    }

    public function update(UpdateTraderGameRequest $request, TraderGame $traderGame)
    {
        $traderGame->update($request->all());

        return redirect()->route('admin.trader-games.index');
    }

    public function show(TraderGame $traderGame)
    {
        abort_if(Gate::denies('trader_game_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.traderGames.show', compact('traderGame'));
    }

    public function destroy(TraderGame $traderGame)
    {
        abort_if(Gate::denies('trader_game_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $traderGame->delete();

        return back();
    }

    public function massDestroy(MassDestroyTraderGameRequest $request)
    {
        TraderGame::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
