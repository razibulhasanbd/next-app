<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBusinessModelRequest;
use App\Http\Requests\UpdateBusinessModelRequest;
use App\Models\BusinessModel;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class BusinessModelController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('business_model_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = BusinessModel::query()->select(sprintf('%s.*', (new BusinessModel())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'business_model_show';
                $editGate = 'business_model_edit';
                $deleteGate = 'business_model_delete';
                $crudRoutePart = 'business-models';

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

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.businessModels.index');
    }

    public function create()
    {
        abort_if(Gate::denies('business_model_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.businessModels.create');
    }

    public function store(StoreBusinessModelRequest $request)
    {
        $businessModel = BusinessModel::create($request->all());

        return redirect()->route('admin.business-models.index');
    }

    public function edit(BusinessModel $businessModel)
    {
        abort_if(Gate::denies('business_model_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.businessModels.edit', compact('businessModel'));
    }

    public function update(UpdateBusinessModelRequest $request, BusinessModel $businessModel)
    {
        $businessModel->update($request->all());

        return redirect()->route('admin.business-models.index');
    }

    public function show(BusinessModel $businessModel)
    {
        abort_if(Gate::denies('business_model_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.businessModels.show', compact('businessModel'));
    }
}
