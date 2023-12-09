<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTypeRequest;
use App\Http\Requests\StoreTypeRequest;
use App\Http\Requests\UpdateTypeRequest;
use App\Models\Type;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TypeController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Type::query()->select(sprintf('%s.*', (new Type())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'type_show';
                $editGate = 'type_edit';
                $deleteGate = 'type_delete';
                $crudRoutePart = 'types';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.types.index');
    }

    public function create()
    {
        abort_if(Gate::denies('type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.types.create');
    }

    public function store(StoreTypeRequest $request)
    {
        $type = Type::create($request->all());

        return redirect()->route('admin.types.index');
    }

    public function edit(Type $type)
    {
        abort_if(Gate::denies('type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.types.edit', compact('type'));
    }

    public function update(UpdateTypeRequest $request, Type $type)
    {
        $type->update($request->all());
        Helper::forgetFaqCache();
        return redirect()->route('admin.types.index');
    }

    public function show(Type $type)
    {
        abort_if(Gate::denies('type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.types.show', compact('type'));
    }

    public function destroy(Type $type)
    {
        abort_if(Gate::denies('type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $type->delete();
        Helper::forgetFaqCache();
        return back();
    }

    public function massDestroy(MassDestroyTypeRequest $request)
    {
        Type::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
