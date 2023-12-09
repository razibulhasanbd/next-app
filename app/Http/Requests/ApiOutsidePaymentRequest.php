<?php

namespace App\Http\Requests;

use App\Constants\AppConstants;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class ApiOutsidePaymentRequest extends FormRequest
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
            'payments_for' => 'required|in:Account TopUp Fee,Account Reset Fee,New Account',
            'payment_method' => 'required',
            'name' => 'required|max:200',
            'email' => 'required|email|max:200',
            'login' => ['required_if:payments_for,Account TopUp Fee,Account Reset Fee', 'integer'],
            'plan_id' => ['required_if:payments_for,New Account', 'integer'],
            'transaction_id' => 'sometimes|nullable|max:244',
            'payment_proof' => 'required|image|max:5120|mimes:jpeg,png,jpg',
            'coupon'=>'sometimes|string|max:50|nullable',
            'country_id'=>'sometimes|nullable',  //TODO we will add exist validation here after sometime
            'password'  => 'nullable|string|max:100',
            'server_name'      => ['required', Rule::in([AppConstants::TRADING_SERVER_MT4,AppConstants::TRADING_SERVER_MT5])],
        ];
    }

    public function failedValidation(Validator $validator)

    {

        throw new HttpResponseException(response()->json([

            'success'   => false,

            'message'   => 'Validation errors',

            'data'      => $validator->errors()

        ], 422),);
    }

    public function messages()

    {

        return [

            'payments_for.required' => 'Payments For is required',
            'payment_method.required' => 'Payments Method is required',
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'payment_proof.required' => 'The Image Must be an Image',

        ];
    }
}
