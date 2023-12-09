<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAccountStatusLogRequest;
use App\Http\Requests\StoreAccountStatusLogRequest;
use App\Http\Requests\UpdateAccountStatusLogRequest;
use App\Models\Account;
use App\Models\AccountStatus;
use App\Models\AccountStatusLog;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AccountStatusLogController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('account_status_log_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AccountStatusLog::with(['account', 'old_status', 'new_status', 'account_status_message'])->select(sprintf('%s.*', (new AccountStatusLog())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'account_status_log_show';
                $editGate = 'account_status_log_edit';
                $deleteGate = 'account_status_log_delete';
                $crudRoutePart = 'account-status-logs';

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
            $table->addColumn('account_customer', function ($row) {
                return $row->account ? $row->account->login : '';
            });

            $table->addColumn('old_status_status', function ($row) {
                return $row->old_status ? $row->old_status->status : '';
            });

            $table->addColumn('new_status_status', function ($row) {
                return $row->new_status ? $row->new_status->status : '';
            });

            $table->addColumn('account_status_message', function ($row) {
                return $row->account_status_message ? $row->account_status_message->message : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'account', 'old_status', 'new_status','account_status_message']);

            return $table->make(true);
        }

        $accounts         = Account::get();
        $account_statuses = AccountStatus::get();

        return view('admin.accountStatusLogs.index', compact('accounts', 'account_statuses'));
    }

    public function create()
    {
        abort_if(Gate::denies('account_status_log_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accounts = Account::pluck('login', 'id')->prepend(trans('global.pleaseSelect'), '');

        $old_statuses = AccountStatus::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $new_statuses = AccountStatus::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.accountStatusLogs.create', compact('accounts', 'new_statuses', 'old_statuses'));
    }

    public function store(StoreAccountStatusLogRequest $request)
    {
        $accountStatusLog = AccountStatusLog::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $accountStatusLog->id]);
        }

        return redirect()->route('admin.account-status-logs.index');
    }

    public function edit(AccountStatusLog $accountStatusLog)
    {
        abort_if(Gate::denies('account_status_log_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accounts = Account::pluck('customer', 'id')->prepend(trans('global.pleaseSelect'), '');

        $old_statuses = AccountStatus::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $new_statuses = AccountStatus::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $accountStatusLog->load('account', 'old_status', 'new_status');

        return view('admin.accountStatusLogs.edit', compact('accountStatusLog', 'accounts', 'new_statuses', 'old_statuses'));
    }

    public function update(UpdateAccountStatusLogRequest $request, AccountStatusLog $accountStatusLog)
    {
        $accountStatusLog->update($request->all());

        return redirect()->route('admin.account-status-logs.index');
    }

    public function show(AccountStatusLog $accountStatusLog)
    {
        abort_if(Gate::denies('account_status_log_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accountStatusLog->load('account', 'old_status', 'new_status');

        return view('admin.accountStatusLogs.show', compact('accountStatusLog'));
    }

    public function destroy(AccountStatusLog $accountStatusLog)
    {
        abort_if(Gate::denies('account_status_log_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accountStatusLog->delete();

        return back();
    }

    public function massDestroy(MassDestroyAccountStatusLogRequest $request)
    {
        AccountStatusLog::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('account_status_log_create') && Gate::denies('account_status_log_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new AccountStatusLog();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
