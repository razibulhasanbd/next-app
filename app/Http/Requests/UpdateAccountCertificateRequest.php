<?php

namespace App\Http\Requests;

use App\Models\AccountCertificate;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAccountCertificateRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('account_certificate_edit');
    }

    public function rules()
    {
        return [
            'certificate_id' => [
                'required',
                'integer',
            ],
            'account_id' => [
                'required',
                'integer',
            ],
            'certificate_data' => [
                'string',
                'required',
            ],
            'customer_id' => [
                'required',
                'integer',
            ],
            'url' => [
                'string',
                'nullable',
            ],
            'share' => [
                'required',
            ],
            'subscription_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
