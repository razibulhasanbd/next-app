<?php

namespace App\Http\Requests;

use App\Models\Plan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePlanRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('plan_edit');
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
            'leverage' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'starting_balance' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'upgrade_threshold' => [
                'numeric',
                'required',
            ],
            'liquidate_friday' => [
                'required',
            ],
            'package_id' => [
                'required',
                'integer',
            ],
            'server_id' => [
                'required',
                'integer',
            ],
            'duration' => [
                'string',
                'required',
            ],
            'next_plan' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'new_account_on_next_plan' => [
                'required',
            ],
        ];
    }
}
