<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAccountLabelRequest;
use App\Http\Requests\StoreAccountLabelRequest;
use App\Http\Requests\UpdateAccountLabelRequest;
use App\Models\Account;
use App\Models\AccountLabel;
use App\Models\Label;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AccountLabelsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('account_label_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AccountLabel::with(['account', 'labels'])->select(sprintf('%s.*', (new AccountLabel())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'account_label_show';
                $editGate = 'account_label_edit';
                $deleteGate = 'account_label_delete';
                $crudRoutePart = 'account-labels';

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
            $table->addColumn('account_login', function ($row) {
                return $row->account ? $row->account->login : '';
            });
            $table->editColumn('label', function ($row) {
                $labels = [];
                foreach ($row->labels as $label) {
                    $labels[] = sprintf('<span class="badge badge-info">%s</span>', $label->title);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'account', 'label']);

            return $table->make(true);
        }

        $accounts = Account::get();
        $labels   = Label::get();

        return view('admin.accountLabels.index', compact('accounts', 'labels'));
    }

    public function create()
    {
        abort_if(Gate::denies('account_label_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accounts = Account::pluck('login', 'id')->prepend(trans('global.pleaseSelect'), '');

        $labels = Label::pluck('title', 'id');

        return view('admin.accountLabels.create', compact('accounts', 'labels'));
    }

    public function store(StoreAccountLabelRequest $request)
    {
        $accountLabel = AccountLabel::create($request->all());
        $accountLabel->labels()->sync($request->input('labels', []));

        return redirect()->route('admin.account-labels.index');
    }

    public function edit(AccountLabel $accountLabel)
    {
       
        abort_if(Gate::denies('account_label_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accounts = Account::pluck('login', 'id')->prepend(trans('global.pleaseSelect'), '');

        $labels = Label::pluck('title', 'id');

        $accountLabel->load('account', 'labels');

        return view('admin.accountLabels.edit', compact('accountLabel', 'accounts', 'labels'));
    }

    public function update(UpdateAccountLabelRequest $request, AccountLabel $accountLabel)
    {
        $accountLabel->update($request->all());
        $accountLabel->labels()->sync($request->input('labels', []));

        return redirect()->route('admin.account-labels.index');
    }

    public function show(AccountLabel $accountLabel)
    {
        abort_if(Gate::denies('account_label_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accountLabel->load('account', 'labels');

        return view('admin.accountLabels.show', compact('accountLabel'));
    }

    public function destroy(AccountLabel $accountLabel)
    {
        abort_if(Gate::denies('account_label_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accountLabel->forceDelete();

        return back();
    }

    public function massDestroy(MassDestroyAccountLabelRequest $request)
    {
        AccountLabel::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeAccountLabel($id)
    {
        abort_if(Gate::denies('account_label_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $accountLabel = AccountLabel::where('account_id',$id)->first();
        $labels = Label::pluck('title', 'id');
        $accountLabel->load('account', 'labels');

        return view('admin.accountLabels.add-edit', compact('accountLabel','labels'));
    }
}
