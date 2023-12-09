<?php

namespace App\Http\Requests;

use App\Models\Type;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTypeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('type_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'description' => [
                'string',
                'required',
            ],
        ];
    }
}
