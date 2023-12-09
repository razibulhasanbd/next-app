<?php

namespace App\Http\Requests;

use App\Models\TargetReachedAccount;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTargetReachedAccountRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('target_reached_account_edit');
    }

    public function rules()
    {
        return [
            'account_id' => [
                'required',
                'integer',
            ],
            'plan_id' => [
                'required',
                'integer',
            ],
            'metric_info' => [
                'string',
                'required',
            ],
            'rules_reached' => [
                'string',
                'required',
            ],
            'subscription_id' => [
                'required',
                'integer',
            ],
            'approved_at' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'denied_at' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
        ];
    }
}
