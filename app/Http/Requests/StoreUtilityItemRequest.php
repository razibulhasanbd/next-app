<?php

namespace App\Http\Requests;

use App\Models\UtilityItem;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreUtilityItemRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('utility_item_create');
    }

    public function rules()
    {
        return [
            'utility_category_id' => [
                'required',
                'integer',
            ],
            'icon_url' => [
                'string',
                'nullable',
            ],
            'header' => [
                'string',
                'required',
            ],
            'description' => [
                'required',
            ],
            'download_file_url' => [
                'file',
                'nullable',
            ],
            'status' => [
                'required',
            ],
            'order_value' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
