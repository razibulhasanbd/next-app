<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreachEvent extends Model
{
    use HasFactory;
    protected $guarded=[];
    public $table = 'breach_events';

    protected $fillable = [
        'account_id',
        'login',
        'balance',
        'equity',
        'metrics',
        'trades'
    ];


    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

}
