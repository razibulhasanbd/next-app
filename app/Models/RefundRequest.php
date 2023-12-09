<?php

namespace App\Models;

use App\Models\User;
use App\Models\Orders;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RefundRequest extends Model
{
    use HasFactory;
    public $table                = 'refund_requests';
    public const STATUS_PENDING  = 0;
    public const STATUS_APPROVED = 1;
    public const STATUS_REJECTED = 2;

    public const REFUND_STATUS_ENABLE  = 1;
    public const REFUND_STATUS_DISABLE = 0;

    protected $fillable = [
        'order_id',
        'user_id',
        'amount',
        'status',
        'comment',
        'reply_comment',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
}
