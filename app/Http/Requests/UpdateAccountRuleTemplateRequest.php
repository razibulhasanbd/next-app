<?php

namespace App\Http\Requests;

use App\Models\AccountRuleTemplate;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAccountRuleTemplateRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('account_rule_template_edit');
    }

    public function rules()
    {
        return [
            'rule_name_id' => [
                'required',
                'integer',
            ],
            'plan_id' => [
                'required',
                'integer',
            ],
            'default_value' => [
                'string',
                'required',
            ],
        ];
    }
}
