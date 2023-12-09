<?php

namespace App\Http\Requests;

use App\Models\RuleName;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateRuleNameRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('rule_name_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'condition' => [
                'string',
                'required',
                'unique:rule_names,condition,' . request()->route('rule_name')->id,
            ],
        ];
    }
}
