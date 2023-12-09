<?php

namespace App\Http\Requests;

use App\Models\RuleName;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyRuleNameRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('rule_name_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:rule_names,id',
        ];
    }
}
