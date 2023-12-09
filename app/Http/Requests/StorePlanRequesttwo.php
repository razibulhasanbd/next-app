<?php

namespace App\Http\Requests;

use App\Models\Plan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePlanRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('plan_create');
    }

    public function rules()
    {
        return [
            'type' => [
                'string',
                'required',
            ],
            'title' => [
                'string',
                'required',
            ],
            'description' => [
                'string',
                'required',
            ],
            'upgradePlanId' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'serverGroupName' => [
                'string',
                'required',
            ],
            'leverage' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'accountMaxDrawdown' => [
                'numeric',
                'required',
            ],
            'accountProfitTarget' => [
                'numeric',
                'required',
            ],
            'startingBalance' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'dailyLossLimit' => [
                'numeric',
                'required',
            ],
            'upgradeThreshold' => [
                'numeric',
                'required',
            ],
            'accumulatedProfit' => [
                'numeric',
                'required',
            ],
            'profitShare' => [
                'numeric',
                'required',
            ],
            'liquidateFriday' => [
                'required',
            ],
        ];
    }
}
