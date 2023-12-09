<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPlanRequest;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\MtServer;
use App\Models\Package;
use App\Models\Plan;
use Illuminate\Support\Facades\Cache;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PlanController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('plan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Plan::with(['package', 'server'])->select(sprintf('%s.*', (new Plan())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'plan_show';
                $editGate = 'plan_edit';
                $deleteGate = 'plan_delete';
                $crudRoutePart = 'plans';

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
            $table->editColumn('type', function ($row) {
                return $row->type ? $row->type : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });
            $table->editColumn('leverage', function ($row) {
                return $row->leverage ? $row->leverage : '';
            });
            $table->editColumn('startingBalance', function ($row) {
                return $row->startingBalance ? $row->startingBalance : '';
            });
            $table->addColumn('package_name', function ($row) {
                return $row->package ? $row->package->name : '';
            });

            $table->addColumn('server_friendly_name', function ($row) {
                return $row->server ? $row->server->friendly_name : '';
            });

            $table->editColumn('duration', function ($row) {
                return $row->duration ? $row->duration : '';
            });
            $table->editColumn('next_plan', function ($row) {
                return $row->next_plan ? $row->next_plan : '';
            });
            $table->editColumn('new_account_on_next_plan', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->new_account_on_next_plan ? 'checked' : null) . '>';
            });

            $table->rawColumns(['actions', 'placeholder', 'package', 'server', 'new_account_on_next_plan']);

            return $table->make(true);
        }

        $packages   = Package::get();
        $mt_servers = MtServer::get();

        return view('admin.plans.index', compact('packages', 'mt_servers'));
    }

    public function create()
    {
        abort_if(Gate::denies('plan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $packages = Package::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $servers = MtServer::pluck('friendly_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.plans.create', compact('packages', 'servers'));
    }

    public function store(StorePlanRequest $request)
    {
        $plan = Plan::create($request->all());

        return redirect()->route('admin.plans.index');
    }

    public function edit(Plan $plan)
    {
        abort_if(Gate::denies('plan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $packages = Package::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $servers = MtServer::pluck('friendly_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $plan->load('package', 'server');

        return view('admin.plans.edit', compact('packages', 'plan', 'servers'));
    }

    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $plan->update($request->all());
        Cache::forget($plan->cacheKey() . ':plan_rules');

        return redirect()->route('admin.plans.index');
    }

    public function show(Plan $plan)
    {
        abort_if(Gate::denies('plan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $plan->load('package', 'server','planRule.ruleName');
        return view('admin.plans.show', compact('plan'));
    }

    public function destroy(Plan $plan)
    {
        abort_if(Gate::denies('plan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $plan->delete();
        Cache::forget($plan->cacheKey() . ':plan_rules');

        return back();
    }

    public function massDestroy(MassDestroyPlanRequest $request)
    {
        Plan::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
