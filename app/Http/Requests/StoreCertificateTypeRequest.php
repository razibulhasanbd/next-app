<?php

namespace App\Http\Requests;

use App\Models\CertificateType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCertificateTypeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('certificate_type_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'label' => [
                'string',
                'nullable',
            ],
        ];
    }
}
