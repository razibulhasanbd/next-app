<?php

namespace App\Http\Requests;

use App\Models\AccountStatusMessage;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAccountStatusMessageRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('account_status_message_create');
    }

    public function rules()
    {
        return [
            'message' => [
                'required',
            ],
        ];
    }
}
