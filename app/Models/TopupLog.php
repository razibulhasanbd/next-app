<?php

namespace App\Models;

use App\Models\Account;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TopupLog extends Model
{
    use HasFactory;
    use Auditable;
    protected $guarded = [];
    public $table = 'topup_logs';


    protected $fillable = [
        'account_id',
        'last_metric',
        'breach_metric',
        'topup_amount',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
