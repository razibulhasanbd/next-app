<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\StoreUtilityCategoryRequest;
use App\Http\Requests\UpdateUtilityCategoryRequest;
use App\Models\UtilityCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class UtilityCategoryController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('utility_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = UtilityCategory::query()->select(sprintf('%s.*', (new UtilityCategory())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'utility_category_show';
                $editGate = 'utility_category_edit';
                $deleteGate = 'utility_category_delete';
                $crudRoutePart = 'utility-categories';

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
            $table->editColumn('order_value', function ($row) {
                return $row->order_value ? $row->order_value : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? UtilityCategory::STATUS_SELECT[$row->status] : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.utilityCategories.index');
    }

    public function create()
    {
        abort_if(Gate::denies('utility_category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.utilityCategories.create');
    }

    public function store(StoreUtilityCategoryRequest $request)
    {
        $utilityCategory = UtilityCategory::create($request->all());

        return redirect()->route('admin.utility-categories.index');
    }

    public function edit(UtilityCategory $utilityCategory)
    {
        abort_if(Gate::denies('utility_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.utilityCategories.edit', compact('utilityCategory'));
    }

    public function update(UpdateUtilityCategoryRequest $request, UtilityCategory $utilityCategory)
    {
        $utilityCategory->update($request->all());

        return redirect()->route('admin.utility-categories.index');
    }

    public function show(UtilityCategory $utilityCategory)
    {
        abort_if(Gate::denies('utility_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.utilityCategories.show', compact('utilityCategory'));
    }
}
