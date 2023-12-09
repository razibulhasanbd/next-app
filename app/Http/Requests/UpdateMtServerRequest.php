<?php

namespace App\Http\Requests;

use App\Models\MtServer;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateMtServerRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('mt_server_edit');
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
