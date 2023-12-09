<?php

namespace App\Http\Requests;

use App\Models\AccountRule;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAccountRuleRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('account_rule_edit');
    }

    public function rules()
    {
        return [
            'login' => [
                'required',
                'integer',
                'exists:accounts,login',
            ],
            'rule_id' => [
                'required',
                'integer',
            ],
            'value' => [
                'string',
                'required',
            ],
        ];
    }
}
