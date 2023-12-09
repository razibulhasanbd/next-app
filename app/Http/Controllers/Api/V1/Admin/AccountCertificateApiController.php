<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAccountCertificateRequest;
use App\Http\Requests\UpdateAccountCertificateRequest;
use App\Http\Resources\Admin\AccountCertificateResource;
use App\Models\AccountCertificate;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountCertificateApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('account_certificate_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AccountCertificateResource(AccountCertificate::with(['certificate', 'account', 'customer'])->get());
    }

    public function store(StoreAccountCertificateRequest $request)
    {
        $accountCertificate = AccountCertificate::create($request->all());

        return (new AccountCertificateResource($accountCertificate))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AccountCertificate $accountCertificate)
    {
        abort_if(Gate::denies('account_certificate_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AccountCertificateResource($accountCertificate->load(['certificate', 'account', 'customer']));
    }

    public function update(UpdateAccountCertificateRequest $request, AccountCertificate $accountCertificate)
    {
        $accountCertificate->update($request->all());

        return (new AccountCertificateResource($accountCertificate))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(AccountCertificate $accountCertificate)
    {
        abort_if(Gate::denies('account_certificate_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accountCertificate->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
