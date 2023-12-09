<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanRule extends Model
{
    //use SoftDeletes;
    use Auditable;
    use HasFactory;

    public $table = 'plan_rules';
    protected $with = ['ruleName'];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    // protected $touches = ['ruleName','plan'];
    protected $fillable = [
        'rule_name_id',
        'plan_id',
        'value',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function ruleName()
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
