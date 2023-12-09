<?php

namespace App\Http\Requests;

use App\Models\AccountStatus;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAccountStatusRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('account_status_edit');
    }

    public function rules()
    {
        return [
            'status' => [
                'string',
                'required',
            ],
        ];
    }
}
