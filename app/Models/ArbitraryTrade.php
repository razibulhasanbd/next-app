<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArbitraryTrade extends Model
{
    use HasFactory;
    public $table = 'arbitrary_trades';

    protected $fillable = ['account_id','login','ticket','time_difference'];

}
