<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{

    public function toArray($request)
    {
        if($this->payment_method_form_type == 'bank_transfer'){
            $bankInfo = $this->data ? json_decode($this->data) : null;
            $data = [
                'bank_name' => $bankInfo->bank_name ?? '',
                'account_type' => $bankInfo->account_type ?? '',
                'account_number' => $bankInfo->account_number ?? '',
                'routing_number' => $bankInfo->routing_number ?? '',
                'iban' => $bankInfo->iban ?? '',
                'swift_code' => $bankInfo->swift_code ?? '',
                'beneficiary_name' => $bankInfo->beneficiary_name ?? '',
                'beneficiary_email' => $bankInfo->beneficiary_email ?? '',
                'beneficiary_address' => $bankInfo->beneficiary_address ?? '',
            ];
        }

        return [
            'id' => $this->id,
            'order_number' => $this->serial_number,
            'name' => $this->name,
            'method' => $this->payment_method,
            'commission' => $this->commission,
            'address' => $this->address,
            'qrCodeInstruction' => $this->qr_code_instructions ? json_decode($this->qr_code_instructions) : [],
            'data' => $data ?? null,
            'icon' => $this->icon,

        ];
    }
}
