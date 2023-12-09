<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreModelVarientRequest;
use App\Http\Requests\UpdateModelVarientRequest;
use App\Models\BusinessModel;
use App\Models\ModelVarient;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ModelVarientController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('model_varient_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ModelVarient::with(['business_model'])->select(sprintf('%s.*', (new ModelVarient())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'model_varient_show';
                $editGate = 'model_varient_edit';
                $deleteGate = 'model_varient_delete';
                $crudRoutePart = 'model-varients';

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
            $table->addColumn('business_model_name', function ($row) {
                return $row->business_model ? $row->business_model->name : '';
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('is_default', function ($row) {
                return $row->is_default ? ModelVarient::IS_DEFAULT_RADIO[$row->is_default] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'business_model']);

            return $table->make(true);
        }

        $business_models = BusinessModel::get();

        return view('admin.modelVarients.index', compact('business_models'));
    }

    public function create()
    {
        abort_if(Gate::denies('model_varient_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $business_models = BusinessModel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.modelVarients.create', compact('business_models'));
    }

    public function store(StoreModelVarientRequest $request)
    {
        $modelVarient = ModelVarient::create($request->all());

        return redirect()->route('admin.model-varients.index');
    }

    public function edit(ModelVarient $modelVarient)
    {
        abort_if(Gate::denies('model_varient_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $business_models = BusinessModel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $modelVarient->load('business_model');

        return view('admin.modelVarients.edit', compact('business_models', 'modelVarient'));
    }

    public function update(UpdateModelVarientRequest $request, ModelVarient $modelVarient)
    {
        $modelVarient->update($request->all());

        return redirect()->route('admin.model-varients.index');
    }

    public function show(ModelVarient $modelVarient)
    {
        abort_if(Gate::denies('model_varient_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $modelVarient->load('business_model');

        return view('admin.modelVarients.show', compact('modelVarient'));
    }
}
