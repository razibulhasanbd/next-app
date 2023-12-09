<?php

namespace App\Http\Requests;

use App\Models\Retake;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateRetakeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('retake_edit');
    }

    public function rules()
    {
        return [
            'account_id' => [
                'required',
                'integer',
            ],
            'subscription_id' => [
                'required',
                'integer',
            ],
            'retake_count' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
