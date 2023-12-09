<?php

namespace App\Services\Checkout;

use App\Constants\AppConstants;
use App\Models\Coupon;
use App\Models\JlPlan;
use App\Traits\Auditable;
use Carbon\Carbon;

class CouponService
{
    use Auditable;

    /**
     * coupon validate check and return discount amount
     *
     * @param string $coupon_code
     * @return Coupon|null
     */
    public static function couponValidateCheck(string $coupon_code) : Coupon|null
    {
        $coupon =  Coupon::where('code', $coupon_code)->where('status', Coupon::STATUS_ENABLE)->first();
        if($coupon && $coupon->expiry_date < Carbon::now()){
            return null;
        }
        return $coupon;
    }



    /**
     * return discount price
     * @param Coupon $coupon
     * @param JlPlan $plan
     *
     * @return array
     */
    public static function couponPrice(Coupon $coupon, JlPlan $plan){
        if ($coupon->type == AppConstants::COUPON_FLAT) {
            $payable_amount = ($plan->price - $coupon->amount);
            $coupon_amount  = $coupon->amount;
        } else { // percentage
            $discount       = ($plan->price * ($coupon->amount / 100));
            $amount         = $plan->price - $discount;
            $payable_amount = $amount;
            $coupon_amount  = $discount;
        }
        return[
            'old_amount'     => $plan->price,
            'coupon_amount'  => $coupon_amount,
            'payable_amount' => $payable_amount,
        ];
    }
    public function getCouponInfo($coupon_id){
        $customer = Coupon::find($coupon_id);
        $exp        = explode(' ', $customer->name);
        $first_name = '';
        $last_name  = '';

        return response()->json(['status'=> true, 'data'=> ['first_name' => $first_name, 'last_name'  => $last_name]]);
    }

}
