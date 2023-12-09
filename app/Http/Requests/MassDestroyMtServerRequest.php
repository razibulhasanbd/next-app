<?php

namespace App\Http\Requests;

use App\Models\MtServer;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyMtServerRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('mt_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:mt_servers,id',
        ];
    }
}
