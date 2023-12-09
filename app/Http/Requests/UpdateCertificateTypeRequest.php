<?php

namespace App\Http\Requests;

use App\Models\CertificateType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCertificateTypeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('certificate_type_edit');
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
