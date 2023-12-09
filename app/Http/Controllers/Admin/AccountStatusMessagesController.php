<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAccountStatusMessageRequest;
use App\Http\Requests\StoreAccountStatusMessageRequest;
use App\Http\Requests\UpdateAccountStatusMessageRequest;
use App\Models\AccountStatusMessage;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AccountStatusMessagesController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('account_status_message_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AccountStatusMessage::query()->select(sprintf('%s.*', (new AccountStatusMessage())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'account_status_message_show';
                $editGate = 'account_status_message_edit';
                $deleteGate = 'account_status_message_delete';
                $crudRoutePart = 'account-status-messages';

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

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.accountStatusMessages.index');
    }

    public function create()
    {
        abort_if(Gate::denies('account_status_message_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.accountStatusMessages.create');
    }

    public function store(StoreAccountStatusMessageRequest $request)
    {
        $accountStatusMessage = AccountStatusMessage::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $accountStatusMessage->id]);
        }

        return redirect()->route('admin.account-status-messages.index');
    }

    public function edit(AccountStatusMessage $accountStatusMessage)
    {
        abort_if(Gate::denies('account_status_message_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.accountStatusMessages.edit', compact('accountStatusMessage'));
    }

    public function update(UpdateAccountStatusMessageRequest $request, AccountStatusMessage $accountStatusMessage)
    {
        $accountStatusMessage->update($request->all());

        return redirect()->route('admin.account-status-messages.index');
    }

    public function show(AccountStatusMessage $accountStatusMessage)
    {
        abort_if(Gate::denies('account_status_message_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.accountStatusMessages.show', compact('accountStatusMessage'));
    }

    public function destroy(AccountStatusMessage $accountStatusMessage)
    {
        abort_if(Gate::denies('account_status_message_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accountStatusMessage->delete();

        return back();
    }

    public function massDestroy(MassDestroyAccountStatusMessageRequest $request)
    {
        AccountStatusMessage::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('account_status_message_create') && Gate::denies('account_status_message_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new AccountStatusMessage();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
