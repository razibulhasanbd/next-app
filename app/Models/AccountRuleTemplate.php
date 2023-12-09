<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountRuleTemplate extends Model
{
    use SoftDeletes;
    //use Auditable;
    use HasFactory;

    public $table = 'account_rule_templates';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'rule_name_id',
        'plan_id',
        'default_value',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function rule_name()
    {
        return $this->belongsTo(RuleName::class, 'rule_name_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
