<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountStatus extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public $table = 'account_statuses';

    /** this const variable's value is the same as account_statuses table ID. If you add new more entity in account_statuses table & AccountStatusSeeder class(also you can create with new seeder), you will have to create new const here also */
    public const CREATED                                = 1;
    public const RUNNING                                = 2;
    public const RESET                                  = 2;
    public const PAUSED                                 = 3;
    public const CANCELED                               = 5;
    public const MIGRATED                               = 6;
    public const SCALED_UP                              = 7;
    public const EVALUATION_PHASE_1_2_MIGRATION_REQUEST = 8;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
