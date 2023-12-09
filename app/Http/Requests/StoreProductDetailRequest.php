<?php

namespace App\Http\Requests;

use App\Models\ProductDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProductDetailRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_detail_create');
    }

    public function rules()
    {
        return [
            'product_id' => [
                'required',
                'integer',
            ],
            'title' => [
                'string',
                'min:5',
                'max:255',
                'required',
            ],
            'description' => [
                'required',
            ],
            'value' => [
                'string',
                'min:1',
                'max:250',
                'required',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
