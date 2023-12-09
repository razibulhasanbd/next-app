<?php

namespace App\Http\Requests;

use App\Models\ModelVarient;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyModelVarientRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('model_varient_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:model_varients,id',
        ];
    }
}
