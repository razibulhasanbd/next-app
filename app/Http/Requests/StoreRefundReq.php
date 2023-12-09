<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRefundReq extends FormRequest
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
            'amount'   => 'required|numeric',
            'comment'  => 'required|max:100',
            'order_id' => 'required|integer|exists:orders,id',
        ];
    }

    public function messages()
    {
        return [
            'amount.required'   => 'Amount Must be required',
            'comment.required'  => 'comment Must be required',
            'comment.max'       => 'Comment filed max character limit 100!.',
            'order_id.required' => 'Order id required',
        ];
    }
}
