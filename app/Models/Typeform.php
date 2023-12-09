<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Typeform extends Model
{
    use SoftDeletes;
    //use InteractsWithMedia;
    use Auditable;
    use HasFactory;

    public const PAYMENT_VERIFICATION_SELECT = [
        '0' => 'pending',
        '1' => 'verified',
        '2' => 'not verified',
        '3' => 'duplicate',
    ];

    public $table = 'typeforms';



    protected $dates = [
        //'approved_at',
        'denied_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'payments_for',
        'funding_package',
        'funding_amount',
        'coupon_code',
        'payment_proof',
        'payment_method',
        'paid_amount',
        'name',
        'email',
        'country_id',
        'country',
        'login',
        'payment_verification',
        'approved_at',
        'transaction_id',
        'denied_at',
        'remarks',
        'server_name',
        'password',
        'archived_at',
        'referred_by',
        'plan_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];



    // public function getApprovedAtAttribute($value)
    // {
    //     return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    // }

    // public function setApprovedAtAttribute($value)
    // {
    //     $this->attributes['approved_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    // }

    public function getDeniedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setDeniedAtAttribute($value)
    {
        $this->attributes['denied_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

}
