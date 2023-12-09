<?php

namespace App\Http\Requests;

use App\Models\MtServer;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreMtServerRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('mt_server_create');
    }

    public function rules()
    {
        return [
            'url' => [
                'string',
                'required',
            ],
            'login' => [
                'string',
                'required',
            ],
            'password' => [
                'required',
            ],
            'server' => [
                'string',
                'required',
            ],
            'group' => [
                'string',
                'required',
            ],
            'friendly_name' => [
                'string',
                'required',
            ],
            'trading_server_type' => [
                'string',
                'required',
            ],
        ];
    }
}
