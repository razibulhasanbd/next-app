<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TargetReachedAccount extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    
    public $table = 'target_reached_accounts';

    protected $dates = [
        'approved_at',
        'denied_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'account_id',
        'plan_id',
        'metric_info',
        'approval_category_id',
        'rules_reached',
        'subscription_id',
        'approved_at',
        'denied_at',
        'created_at',
        'updated_at',
        'deleted_at',
        
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function getApprovedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setApprovedAtAttribute($value)
    {
        $this->attributes['approved_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getDeniedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setDeniedAtAttribute($value)
    {
        $this->attributes['denied_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function approval_category()
    {
        return $this->belongsTo(ApprovalCategory::class, 'approval_category_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
