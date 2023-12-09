<?php

namespace App\Http\Requests;

use App\Models\Account;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAccountRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('account_edit');
    }

    public function rules()
    {
        return [
            'customer' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'login' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'password' => [
                'string',
                'required',
            ],
            'type' => [
                'string',
                'required',
            ],
            'plan' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'name' => [
                'string',
                'required',
            ],
            'comment' => [
                'string',
                'nullable',
            ],
            'balance' => [
                'numeric',
                'required',
            ],
            'equity' => [
                'numeric',
                'required',
            ],
            'credit' => [
                'numeric',
                'required',
            ],
            'breached' => [
                'required',
            ],
            'breachedby' => [
                'string',
                'nullable',
            ],
            'trading_server_type' => [
                'string',
                'required',
            ],
        ];
    }
}
