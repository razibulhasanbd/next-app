<?php

namespace App\Http\Requests;

use App\Models\AccountStatusMessage;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAccountStatusMessageRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('account_status_message_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:account_status_messages,id',
        ];
    }
}
