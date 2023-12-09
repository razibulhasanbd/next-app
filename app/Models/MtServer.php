<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MtServer extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public $table = 'mt_servers';
    public const MT5 = 5;

    protected $hidden = [
        'password',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'url',
        'login',
        'password',
        'server',
        'group',
        'friendly_name',
        'trading_server_type',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function plans()
    {
        return $this->hasMany(Plan::class, 'server_id');
    }

    public function accounts()
    {

        return $this->hasManyThrough(Account::class, Plan::class, 'server_id', 'plan_id', 'id', 'id');
    }


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
