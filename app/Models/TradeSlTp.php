<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradeSlTp extends Model
{
    use SoftDeletes;
    //use Auditable;
    use HasFactory;

    public $table = 'trade_sl_tps';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'trade_id',
        'type',
        'value',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function trade()
    {
        return $this->belongsTo(Trade::class, 'trade_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
