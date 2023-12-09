<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCeritificateRequest;
use App\Http\Requests\UpdateCeritificateRequest;
use App\Http\Resources\Admin\CeritificateResource;
use App\Models\Ceritificate;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CeritificateApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('ceritificate_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CeritificateResource(Ceritificate::with(['type'])->get());
    }

    public function store(StoreCeritificateRequest $request)
    {
        $ceritificate = Ceritificate::create($request->all());

        return (new CeritificateResource($ceritificate))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Ceritificate $ceritificate)
    {
        abort_if(Gate::denies('ceritificate_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CeritificateResource($ceritificate->load(['type']));
    }

    public function update(UpdateCeritificateRequest $request, Ceritificate $ceritificate)
    {
        $ceritificate->update($request->all());

        return (new CeritificateResource($ceritificate))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Ceritificate $ceritificate)
    {
        abort_if(Gate::denies('ceritificate_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ceritificate->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
