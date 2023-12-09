<?php

namespace App\Http\Requests;

use App\Models\Ceritificate;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCeritificateRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ceritificate_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'html_markup' => [
                'string',
                'required',
            ],
            'demo_image' => 'sometimes|mimes:jpeg,jpg,png|max:500',
        ];
    }
}
