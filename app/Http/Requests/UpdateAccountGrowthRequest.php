<?php

namespace App\Http\Requests;

use App\Models\AccountGrowth;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAccountGrowthRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('account_growth_edit');
    }

    public function rules()
    {
        return [
            'account_id' => [
                'required',
                'integer',
            ],
            'amount' => [
                'required',
            ],
            'date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'plan_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
