<?php

namespace App\Http\Requests;

use App\Models\Trade;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class UpdatePaymentMethodRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('payment_method_update');
    }

    public function rules()
    {
        $id = $this->route('id');
        return [
            'name' => 'required|max:50',
            'status' => 'nullable|in:1,0',
            'commission' => 'required|numeric|min:0|max:100',
            'icon' => 'nullable|file|max:1024',
            'account_number' => 'nullable|max:25|required_if:payment_method_form_type,bank_transfer',
            'routing_number' => 'nullable|max:15|required_if:payment_method_form_type,bank_transfer',
            'account_type' => 'nullable|max:15|required_if:payment_method_form_type,bank_transfer',
            'iban' => 'nullable|max:36|required_if:payment_method_form_type,bank_transfer',
            'swift_code' => 'nullable|max:15|required_if:payment_method_form_type,bank_transfer',
            'bank_name' => 'nullable|max:50|required_if:payment_method_form_type,bank_transfer',
            'beneficiary_name' => 'nullable|max:50|required_if:payment_method_form_type,bank_transfer',
            'beneficiary_address' => 'nullable|max:100|required_if:payment_method_form_type,bank_transfer',
            'qr_code_instructions' => 'nullable|array',
            'qr_code_instructions.*' => 'nullable|string',
            'address' => ($this->request->get('payment_method_form_type') == 'bank_transfer') ? 'nullable|max:191' : 'required|max:191',
            'country_category' => [
                'required',
            ],
            'payment_method_form_type' => [
                'required',
            ],
            Rule::unique('payment_methods')->where(function ($query) use ($id) {
                return $query->where('country_category', $this->input('country_category'))
                    ->where('payment_method_form_type', $this->input('payment_method_form_type'))
                    ->where('name', $this->input('name'))
                    ->where('id', '!=', $id);
            })
        ];


    }
}
