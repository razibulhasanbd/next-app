<?php

namespace App\Http\Requests;

use App\Models\AccountRule;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAccountRuleRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('account_rule_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:account_rules,id',
        ];
    }
}
