<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsCalendar extends Model
{
    //use SoftDeletes;
    use Auditable;
    use HasFactory;

    public $table = 'news_calendars';

    protected $dates = [
       // 'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'country',
        'date',
        'impact',
        'forecast',
        'previous',
        'is_restricted',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // public function getDateAttribute($value)
    // {
    //     return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    // }

    // public function setDateAttribute($value)
    // {
    //     $this->attributes['date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    // }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
