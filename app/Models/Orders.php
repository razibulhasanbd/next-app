<?php

namespace App\Models;

use App\Models\Coupon;
use App\Models\Account;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orders extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'orders';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'customer_id',
        'account_id',
        'server_name',
        'coupon_id',
        'order_type',
        'gateway',
        'parent_order_id',
        'billing_address',
        'transaction_id',
        'parent_order_id',
        'total',
        'discount',
        'grand_total',
        'status',
        'jl_plan_id',
        'remarks',
    ];

    public const STATUS_DISABLE = 0;
    public const STATUS_ENABLE  = 1;
    public const STATUS_PENDING = 2;
    public const ANALYTICS_DATA_LIMITS = 30;

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function jlPlans(){
        return $this->belongsTo(JlPlan::class, 'jl_plan_id', 'id');
    }

    public function refundRequest()
    {
        return $this->hasMany(RefundRequest::class,'order_id','id');
    }

    public function refundRequestApprove()
    {
        return $this->hasMany(RefundRequest::class,'order_id','id')->where('status', 1);
    }


    public function addCharges()
    {
        return $this->hasMany(AddExtraCharge::class,'order_id','id');
    }

    public function scopeOldPendingOrders($query, $intervalMinutes = 5){
        return $query->where('status', self::STATUS_PENDING)->where('created_at', '<=', Carbon::now()->subMinutes($intervalMinutes)->toDateTimeString());
    }
    
}
