<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountRule extends Model
{
    //use SoftDeletes;
    use Auditable;
    use HasFactory;

    public $table = 'account_rules';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $with = ['rule'];

    protected $fillable = [
        'account_id',
        'rule_id',
        'value',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    // protected $touches = ['rules','plan'];



    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function rule()
    {
        return $this->belongsTo(RuleName::class, 'rule_id');
    }


    public function rules()
    {
        return $this->belongsTo(RuleName::class, 'rule_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
