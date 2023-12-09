<?php

namespace App\Models;

use App\Models\Coupon;
use App\Models\Account;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerKycs extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'customer_kycs';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'account_id',
        'kyc_response',
        'status',
        'approval_status',
        'user_agreement',
        'veriff_id',
        'pdf_path',
    ];

    public const STATUS_ENABLE  = 1;
    public const STATUS_DISABLE = 0;

    public const TYPE_KYC  = 'kyc';
    public const TYPE_FORM = 'form';
    public const TYPE_UNDER_REVIEW = 'under_review';
    public const TYPE_APPROVED = 'approved';
    public const TYPE_TERMINATE = 'terminate';

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
