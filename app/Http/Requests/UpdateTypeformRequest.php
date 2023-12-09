<?php

namespace App\Http\Requests;

use App\Models\Typeform;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTypeformRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('typeform_edit');
    }

    public function rules()
    {
        return [
            'payments_for' => [
                'string',
                'required',
            ],
            'funding_package' => [
                'string',
                'nullable',
            ],
            'funding_amount' => [
                'string',
                'nullable',
            ],
            'coupon_code' => [
                'string',
                'nullable',
            ],
            'payment_method' => [
                'string',
                'required',
            ],
            'payment_proof' => [
                'required',
            ],
            'paid_amount' => [
                'string',
                'required',
            ],
            'name' => [
                'string',
                'required',
            ],
            'email' => [
                'string',
                'required',
            ],
            'country' => [
                'string',
                'required',
            ],
            'login' => [
                'string',
                'nullable',
            ],
            'approved_at' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'transaction' => [
                'string',
                'required',
            ],
            'denied_at' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'archived_at' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'referred_by' => [
                'string',
                'nullable',
            ],
        ];
    }
}
