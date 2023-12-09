<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyApprovalCategoryRequest;
use App\Http\Requests\StoreApprovalCategoryRequest;
use App\Http\Requests\UpdateApprovalCategoryRequest;
use App\Models\ApprovalCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ApprovalCategoryController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('approval_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ApprovalCategory::query()->select(sprintf('%s.*', (new ApprovalCategory())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'approval_category_show';
                $editGate = 'approval_category_edit';
                $deleteGate = 'approval_category_delete';
                $crudRoutePart = 'approval-categories';

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

        return view('admin.approvalCategories.index');
    }

    public function create()
    {
        abort_if(Gate::denies('approval_category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.approvalCategories.create');
    }

    public function store(StoreApprovalCategoryRequest $request)
    {
        $approvalCategory = ApprovalCategory::create($request->all());

        return redirect()->route('admin.approval-categories.index');
    }

    public function edit(ApprovalCategory $approvalCategory)
    {
        abort_if(Gate::denies('approval_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.approvalCategories.edit', compact('approvalCategory'));
    }

    public function update(UpdateApprovalCategoryRequest $request, ApprovalCategory $approvalCategory)
    {
        $approvalCategory->update($request->all());

        return redirect()->route('admin.approval-categories.index');
    }

    public function show(ApprovalCategory $approvalCategory)
    {
        abort_if(Gate::denies('approval_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.approvalCategories.show', compact('approvalCategory'));
    }

    public function destroy(ApprovalCategory $approvalCategory)
    {
        abort_if(Gate::denies('approval_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $approvalCategory->delete();

        return back();
    }

    public function massDestroy(MassDestroyApprovalCategoryRequest $request)
    {
        ApprovalCategory::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
