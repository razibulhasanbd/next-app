<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OutsidePaymentGoogleSheetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $paymentVerificationArray=[
            0 => 'Pending',
            1 => 'Verified',
            2 => 'Not Verified',
            3 => 'Duplicate',
        ];

        return [
            'Id'                   => $this->id,
            'Payments For'         => $this->payments_for,
            'Funding Package'      => $this->funding_package,
            'Funding Amount'       => $this->funding_amount,
            'Coupon Code'          => $this->coupon_code,
            'Payment Method'       => $this->payment_method,
            'Payment Proof'        => $this->payment_proof,
            'Paid Amount'          => $this->paid_amount,
            'Name'                 => $this->name,
            'Email'                => $this->email,
            'Country'              => $this->country,
            'Login'                => $this->login,
            'Payment Verification' => $paymentVerificationArray[$this->payment_verification],
            'Approved At'          => $this->approved_at,
            'Transaction Id'       => $this->transaction_id,
            'Denied At'            => $this->denied_at,
            'Aemarks'              => $this->remarks,
            'Archived At'          => $this->archived_at,
            'Referred By'          => $this->referred_by,
            'Plan'                 => $this->plan_title ?? "",
            "Created At"           => $this->created_at,
            "Updated At"           => $this->updated_at,
            "Deleted At"           => $this->deleted_at,
        ];
    }
}
