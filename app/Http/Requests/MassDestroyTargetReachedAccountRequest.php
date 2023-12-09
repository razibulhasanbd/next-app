<?php

namespace App\Http\Requests;

use App\Models\TargetReachedAccount;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTargetReachedAccountRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('target_reached_account_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:target_reached_accounts,id',
        ];
    }
}
