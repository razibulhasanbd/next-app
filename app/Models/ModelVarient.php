<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelVarient extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public const IS_DEFAULT_RADIO = [
        '1' => 'Enable',
        '2' => 'Disable',
    ];

    public $table = 'model_varients';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'business_model_id',
        'name',
        'is_default',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function business_model()
    {
        return $this->belongsTo(BusinessModel::class, 'business_model_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
