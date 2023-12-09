<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyRuleNameRequest;
use App\Http\Requests\StoreRuleNameRequest;
use App\Http\Requests\UpdateRuleNameRequest;
use App\Models\RuleName;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RuleNameController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('rule_name_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = RuleName::query()->select(sprintf('%s.*', (new RuleName())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'rule_name_show';
                $editGate = 'rule_name_edit';
                $deleteGate = 'rule_name_delete';
                $crudRoutePart = 'rule-names';

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
            $table->editColumn('is_percent', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->is_percent ? 'checked' : null) . '>';
            });
            $table->editColumn('condition', function ($row) {
                return $row->condition ? $row->condition : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'is_percent']);

            return $table->make(true);
        }

        return view('admin.ruleNames.index');
    }

    public function create()
    {
        abort_if(Gate::denies('rule_name_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.ruleNames.create');
    }

    public function store(StoreRuleNameRequest $request)
    {
        $ruleName = RuleName::create($request->all());

        return redirect()->route('admin.rule-names.index');
    }

    public function edit(RuleName $ruleName)
    {
        abort_if(Gate::denies('rule_name_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.ruleNames.edit', compact('ruleName'));
    }

    public function update(UpdateRuleNameRequest $request, RuleName $ruleName)
    {
        $ruleName->update($request->all());

        return redirect()->route('admin.rule-names.index');
    }

    public function show(RuleName $ruleName)
    {
        abort_if(Gate::denies('rule_name_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.ruleNames.show', compact('ruleName'));
    }

    public function destroy(RuleName $ruleName)
    {
        abort_if(Gate::denies('rule_name_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruleName->delete();

        return back();
    }

    public function massDestroy(MassDestroyRuleNameRequest $request)
    {
        RuleName::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
