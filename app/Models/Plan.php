<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use App\Traits\ProvideCacheKey;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;
    use ProvideCacheKey;
    public const LIQUIDATE_FRIDAY_RADIO = [];
    // protected $with = ['server'];
    public $table = 'plans';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'type',
        'title',
        'description',
        'leverage',
        'starting_balance',
        'upgrade_threshold',
        'liquidate_friday',
        'package_id',
        'server_id',
        'duration',
        'next_plan',
        'new_account_on_next_plan',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const EV_P1='Evaluation P1';
    public const EV_P2='Evaluation P2';
    public const EV_REAL='Evaluation Real';
    public const EX_DEMO='Express Demo';
    public const EX_REAL='Express Real';




    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function getCachedRulesAttribute()
    {
        return Cache::remember($this->cacheKey() . ':plan_rules',  300, function () {
            return $this->rules;
        });
    }
    public function breachRules()
    {
        return $this->belongsToMany(RuleName::class, 'plan_rules', 'plan_id', 'rule_name_id')->withPivot('value');
    }
    public function rules()
    {

        return $this->belongsToMany(RuleName::class, 'plan_rules', 'plan_id', 'rule_name_id')->withPivot('value');
    }
    
    public function server()
    {
        return $this->belongsTo(MtServer::class, 'server_id');
    }

    public function mt5server()
    {
        return $this->belongsTo(MtServer::class, 'mt5_server_id');
    }

    public function planRule()
    {
        return $this->hasMany(PlanRule::class, 'plan_id');
    }

    public function accounts()
    {
        return $this->hasMany(Account::class, 'plan_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function accountRuleTemplate()
    {
        return $this->hasMany(AccountRuleTemplate::class, 'id');
    }
}
