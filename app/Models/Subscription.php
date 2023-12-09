<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public $table = 'subscriptions';

    protected $dates = [
        'ending_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'account_id',
        'login',
        'plan_id',
        'ending_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getEndingAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEndingAtAttribute($value)
    {
        $this->attributes['ending_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
