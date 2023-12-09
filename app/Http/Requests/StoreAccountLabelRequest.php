<?php

namespace App\Http\Requests;

use App\Models\AccountLabel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAccountLabelRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('account_label_create');
    }

    public function rules()
    {
        return [
            'account_id' => [
                'required',
                'unique:account_labels,account_id',
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
