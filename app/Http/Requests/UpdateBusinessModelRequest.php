<?php

namespace App\Http\Requests;

use App\Models\BusinessModel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateBusinessModelRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('business_model_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'min:4',
                'max:200',
                'required',
            ],
        ];
    }
}
