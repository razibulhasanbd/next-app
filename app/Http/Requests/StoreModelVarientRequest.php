<?php

namespace App\Http\Requests;

use App\Models\ModelVarient;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreModelVarientRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('model_varient_create');
    }

    public function rules()
    {
        return [
            'business_model_id' => [
                'required',
                'integer',
            ],
            'name' => [
                'string',
                'required',
            ],
            'is_default' => [
                'required',
            ],
        ];
    }
}
