<?php

namespace App\Http\Requests;

use App\Models\ExtendCycleLog;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreExtendCycleLogRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('extend_cycle_log_create');
    }

    public function rules()
    {
        return [
            'login_id' => [
                'required',
                'integer',
            ],
            'subcription_id' => [
                'required',
                'integer',
            ],
            'weeks' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'before_subscription' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'after_subscription' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'account_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
