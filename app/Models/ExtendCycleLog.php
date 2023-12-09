<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExtendCycleLog extends Model
{
    use SoftDeletes;
    //use Auditable;
    use HasFactory;

    public const ELIGIBILITY = [
        '1' => 'Enable',
        '0' => 'Disable'
    ];

    public $table = 'extend_cycle_logs';

    protected $dates = [
        'before_subscription',
        'after_subscription',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'login',
        'subcription_id',
        'weeks',
        'before_subscription',
        'after_subscription',
        'account_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function login()
    {
        return $this->belongsTo(Account::class, 'login_id');
    }

    public function subcription()
    {
        return $this->belongsTo(Subscription::class, 'subcription_id');
    }

    public function getBeforeSubscriptionAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setBeforeSubscriptionAttribute($value)
    {
        $this->attributes['before_subscription'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getAfterSubscriptionAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setAfterSubscriptionAttribute($value)
    {
        $this->attributes['after_subscription'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
