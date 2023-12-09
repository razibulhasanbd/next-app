<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyMtServerRequest;
use App\Http\Requests\StoreMtServerRequest;
use App\Http\Requests\UpdateMtServerRequest;
use App\Models\MtServer;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MtServerController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('mt_server_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = MtServer::query()->select(sprintf('%s.*', (new MtServer())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'mt_server_show';
                $editGate = 'mt_server_edit';
                $deleteGate = 'mt_server_delete';
                $crudRoutePart = 'mt-servers';

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
            $table->editColumn('url', function ($row) {
                return $row->url ? $row->url : '';
            });
            $table->editColumn('login', function ($row) {
                return $row->login ? $row->login : '';
            });
            $table->editColumn('server', function ($row) {
                return $row->server ? $row->server : '';
            });
            $table->editColumn('group', function ($row) {
                return $row->group ? $row->group : '';
            });
            $table->editColumn('friendly_name', function ($row) {
                return $row->friendly_name ? $row->friendly_name : '';
            });
            $table->editColumn('trading_server_type', function ($row) {
                return $row->trading_server_type ? $row->trading_server_type : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.mtServers.index');
    }

    public function create()
    {
        abort_if(Gate::denies('mt_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.mtServers.create');
    }

    public function store(StoreMtServerRequest $request)
    {
        $mtServer = MtServer::create($request->all());

        return redirect()->route('admin.mt-servers.index');
    }

    public function edit(MtServer $mtServer)
    {
        abort_if(Gate::denies('mt_server_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.mtServers.edit', compact('mtServer'));
    }

    public function update(UpdateMtServerRequest $request, MtServer $mtServer)
    {
        $mtServer->update($request->all());

        return redirect()->route('admin.mt-servers.index');
    }

    public function show(MtServer $mtServer)
    {
        abort_if(Gate::denies('mt_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.mtServers.show', compact('mtServer'));
    }

    public function destroy(MtServer $mtServer)
    {
        abort_if(Gate::denies('mt_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mtServer->delete();

        return back();
    }

    public function massDestroy(MassDestroyMtServerRequest $request)
    {
        MtServer::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
