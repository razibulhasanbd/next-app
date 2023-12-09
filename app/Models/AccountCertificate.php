<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountCertificate extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public const SHARE_RADIO = [
        '1' => 'Enable',
        '0' => 'Disable'
    ];

    public $table = 'account_certificates';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'certificate_id',
        'account_id',
        'certificate_data',
        'customer_id',
        'url',
        'doc_id',
        'share',
        'trading_public_share',
        'subscription_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function certificate()
    {
        return $this->belongsTo(Ceritificate::class, 'certificate_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
}
