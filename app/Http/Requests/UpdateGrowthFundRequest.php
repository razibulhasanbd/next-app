<?php

namespace App\Http\Requests;

use App\Models\GrowthFund;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateGrowthFundRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('growth_fund_edit');
    }

    public function rules()
    {
        return [
            'amount' => [
                'required',
            ],
            'date' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'account_id' => [
                'required',
                'integer',
            ],
            'subscription_id' => [
                'required',
                'integer',
            ],
            'fund_type' => [
                'string',
                'nullable',
            ],
        ];
    }
}
