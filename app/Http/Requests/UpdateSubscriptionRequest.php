<?php

namespace App\Http\Requests;

use App\Models\Subscription;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSubscriptionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('subscription_edit');
    }

    public function rules()
    {
        return [
            'account' => [
                //'required',
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
            'plan' => [
                //'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'ending_at' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
        ];
    }
}
