<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyRetakeRequest;
use App\Http\Requests\StoreRetakeRequest;
use App\Http\Requests\UpdateRetakeRequest;
use App\Models\Account;
use App\Models\Retake;
use App\Models\Subscription;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RetakeController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('retake_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Retake::with(['account', 'subscription'])->select(sprintf('%s.*', (new Retake())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'retake_show';
                $editGate = 'retake_edit';
                $deleteGate = 'retake_delete';
                $crudRoutePart = 'retakes';

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

            $table->addColumn('subscription_ending_at', function ($row) {
                return $row->subscription ? $row->subscription->ending_at : '';
            });

            $table->editColumn('retake_count', function ($row) {
                return $row->retake_count ? $row->retake_count : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'account', 'subscription']);

            return $table->make(true);
        }

        return view('admin.retakes.index');
    }

    public function create()
    {
        abort_if(Gate::denies('retake_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accounts = Account::pluck('login', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subscriptions = Subscription::pluck('ending_at', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.retakes.create', compact('accounts', 'subscriptions'));
    }

    public function store(StoreRetakeRequest $request)
    {
        $retake = Retake::create($request->all());

        return redirect()->route('admin.retakes.index');
    }

    public function edit(Retake $retake)
    {
        abort_if(Gate::denies('retake_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accounts = Account::pluck('login', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subscriptions = Subscription::pluck('ending_at', 'id')->prepend(trans('global.pleaseSelect'), '');

        $retake->load('account', 'subscription');

        return view('admin.retakes.edit', compact('accounts', 'retake', 'subscriptions'));
    }

    public function update(UpdateRetakeRequest $request, Retake $retake)
    {
        $retake->update($request->all());

        return redirect()->route('admin.retakes.index');
    }

    public function show(Retake $retake)
    {
        abort_if(Gate::denies('retake_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $retake->load('account', 'subscription');

        return view('admin.retakes.show', compact('retake'));
    }

    public function destroy(Retake $retake)
    {
        abort_if(Gate::denies('retake_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $retake->delete();

        return back();
    }

    public function massDestroy(MassDestroyRetakeRequest $request)
    {
        Retake::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
