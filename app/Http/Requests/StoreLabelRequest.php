<?php

namespace App\Http\Requests;

use App\Models\Label;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreLabelRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('label_create');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
            'comment' => [
                'string',
                'nullable',
            ],
        ];
    }
}
