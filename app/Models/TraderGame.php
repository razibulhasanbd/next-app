<?php

namespace App\Models;

use \DateTimeInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TraderGame extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $table = 'trader_games';
    protected $appends = ['results'];
    protected $hidden =['mental_score','tactical_score','created_at','updated_at','deleted_at','dashboard_email'];
    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'dashboard_user',
        'date',
        'dashboard_email',
        'pnl',
        'mental_score',
        'tactical_score',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
    public function getResultsAttribute()
    {
        
        $results=[];
        $results['mental']=$this->mental_score;
        $results['tactical']=$this->tactical_score;

        return $results;

    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
