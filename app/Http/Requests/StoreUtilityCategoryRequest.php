<?php

namespace App\Http\Requests;

use App\Models\UtilityCategory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreUtilityCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('utility_category_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'order_value' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
