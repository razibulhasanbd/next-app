<?php

namespace App\Http\Requests;

use App\Models\AccountRuleTemplate;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAccountRuleTemplateRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('account_rule_template_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:account_rule_templates,id',
        ];
    }
}
