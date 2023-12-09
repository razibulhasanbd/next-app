<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountMetric extends Model
{
    use SoftDeletes;
    //use Auditable;
    use HasFactory;

    public const IS_ACTIVE_TRADING_DAY_RADIO = [
    ];

    public $table = 'account_metrics';

    protected $dates = [
        // 'metric_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'account_id',
        'maxDailyLoss',
        'metricDate',
        'isActiveTradingDay',
        'trades',
        'averageLosingTrade',
        'averageWinningTrade',
        'lastBalance',
        'lastEquity',
        'lastRisk',
        'maxMonthlyLoss',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // public function getMetricDateAttribute($value)
    // {
    //     return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    // }

    // public function setMetricDateAttribute($value)
    // {
    //     $this->attributes['metric_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    // }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function trade()
    {
        return $this->belongsTo(Trade::class, 'trades');
    }
}
