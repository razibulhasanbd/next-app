<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCertificateTypeRequest;
use App\Http\Requests\StoreCertificateTypeRequest;
use App\Http\Requests\UpdateCertificateTypeRequest;
use App\Models\CertificateType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CertificateTypeController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('certificate_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $certificateTypes = CertificateType::all();

        return view('admin.certificateTypes.index', compact('certificateTypes'));
    }

    public function create()
    {
        abort_if(Gate::denies('certificate_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.certificateTypes.create');
    }

    public function store(StoreCertificateTypeRequest $request)
    {
        $certificateType = CertificateType::create($request->all());

        return redirect()->route('admin.certificate-types.index');
    }

    public function edit(CertificateType $certificateType)
    {
        abort_if(Gate::denies('certificate_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.certificateTypes.edit', compact('certificateType'));
    }

    public function update(UpdateCertificateTypeRequest $request, CertificateType $certificateType)
    {
        $certificateType->update($request->all());

        return redirect()->route('admin.certificate-types.index');
    }

    public function show(CertificateType $certificateType)
    {
        abort_if(Gate::denies('certificate_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $certificateType->load('typeCeritificates');

        return view('admin.certificateTypes.show', compact('certificateType'));
    }

    public function destroy(CertificateType $certificateType)
    {
        abort_if(Gate::denies('certificate_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $certificateType->delete();

        return back();
    }

    public function massDestroy(MassDestroyCertificateTypeRequest $request)
    {
        CertificateType::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
