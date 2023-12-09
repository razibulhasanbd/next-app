<?php
namespace App\Services;

use App\Models\AddExtraCharge;
use Illuminate\Support\Facades\Auth;

class AddChargeService
{

    /**
     * Store Extra Charge
     *
     * @param string $payment_id
     * @param float $amount
     * @param integer $status
     * @param string $remarks
     * @return AddExtraCharge
     */
    public function storeExtraChargeAmount(string $order_id, float $amount, string $remarks): AddExtraCharge
    {
        $addExtraCharge = new AddExtraCharge();
        $addExtraCharge->order_id = $order_id;
        $addExtraCharge->amount = $amount;
        $addExtraCharge->user_id = Auth::user()->id;
        $addExtraCharge->remarks = $remarks;
        $addExtraCharge->save();
        return $addExtraCharge;
    }

}