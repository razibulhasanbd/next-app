<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddCharge extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount'   => 'required|numeric|gt:0.5|lte:100000',
            'comment'  => 'required|max:100',
            'order_id' => 'required|integer|exists:orders,id',
        ];
    }

    public function messages()
    {
        return [
            'amount.lte'        => 'Amount  max  limit 100000!.',
            'amount.gt'        =>  'The amount must be greater than 0.5',
            'amount.required'   => 'Amount Must be required',
            'comment.required'  => 'comment Must be required',
            'comment.max'       => 'Comment filed max character limit 100!.',
            'order_id.required' => 'Order id required',
        ];
    }
}
