<?php

namespace App\Http\Requests;

use App\Models\AccountLabel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAccountLabelRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('account_label_edit');
    }

    public function rules()
    {
        return [
            'account_id' => [
                'required',
                'integer',
            ],
            'labels.*' => [
                'integer',
            ],
            'labels' => [
                'required',
                'array',
            ],
        ];
    }
}
