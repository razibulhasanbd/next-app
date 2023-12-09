<?php

namespace App\Http\Requests;

use App\Models\Question;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreQuestionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('question_create');
    }

    public function rules()
    {
        return [
            'question' => [
                'string',
                'required',
            ],
            'answer' => [
                'required',
            ],
            'categories.*' => [
                'integer',
            ],
            'categories' => [
                'required',
                'array',
            ],
            'types.*' => [
                'integer',
            ],
            'types' => [
                'required',
                'array',
            ],
            'tags.*' => [
                'integer',
            ],
            'tags' => [
                'required',
                'array',
            ],
            'section_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
