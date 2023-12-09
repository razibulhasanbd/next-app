<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCertificateTypeRequest;
use App\Http\Requests\UpdateCertificateTypeRequest;
use App\Http\Resources\Admin\CertificateTypeResource;
use App\Models\CertificateType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CertificateTypeApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('certificate_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CertificateTypeResource(CertificateType::all());
    }

    public function store(StoreCertificateTypeRequest $request)
    {
        $certificateType = CertificateType::create($request->all());

        return (new CertificateTypeResource($certificateType))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CertificateType $certificateType)
    {
        abort_if(Gate::denies('certificate_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CertificateTypeResource($certificateType);
    }

    public function update(UpdateCertificateTypeRequest $request, CertificateType $certificateType)
    {
        $certificateType->update($request->all());

        return (new CertificateTypeResource($certificateType))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CertificateType $certificateType)
    {
        abort_if(Gate::denies('certificate_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $certificateType->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
