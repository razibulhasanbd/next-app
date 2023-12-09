<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public const STATUS_RADIO = [
        '1' => 'Enable',
        '2' => 'Disable',
    ];

    public $table = 'products';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'business_model_id',
        'model_varient_id',
        'plan_id',
        'buy_price',
        'topup_price',
        'reset_price',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function business_model()
    {
        return $this->belongsTo(BusinessModel::class, 'business_model_id');
    }

    public function model_varient()
    {
        return $this->belongsTo(ModelVarient::class, 'model_varient_id');
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
