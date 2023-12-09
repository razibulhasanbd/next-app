<?php

namespace App\Http\Requests;

use App\Models\ApprovalCategory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateApprovalCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('approval_category_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
        ];
    }
}
