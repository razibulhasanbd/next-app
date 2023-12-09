<?php

namespace App\Http\Requests;

use App\Models\AccountStatusLog;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAccountStatusLogRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('account_status_log_edit');
    }

    public function rules()
    {
        return [
            'account_id' => [
                'required',
                'integer',
            ],
            'data' => [
                'required',
            ],
            'new_status_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
