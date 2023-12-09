<?php

namespace App\Http\Requests;

use App\Models\Trade;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class UpdateCountryCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'country_category' => 'required|in:1,0',
        ];


    }
}
