<?php

namespace App\Http\Requests;

use App\Models\Product;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'min:5',
                'max:250',
                'required',
            ],
            'business_model_id' => [
                'required',
                'integer',
            ],
            'model_varient_id' => [
                'required',
                'integer',
            ],
            'plan_id' => [
                'required',
                'integer',
            ],
            'buy_price' => [
                'required',
            ],
            'topup_price' => [
                'required',
            ],
            'reset_price' => [
                'required',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
