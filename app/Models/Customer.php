<?php

namespace App\Models;

use App\Constants\AppConstants;
use \DateTimeInterface;
use App\Models\Country;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use SoftDeletes;
    //use Auditable;
    use HasFactory;

    // Tags
    public const TAGS = [
        '0' => 'None',
        '1' => 'Abuser',
        '2' => 'Suspected'
    ];

    public $table = 'customers';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'phone',
        'city',
        'state',
        'address',
        'zip',
        'country_id',
        'country',
        'email',
        'password',
        'tags',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function accounts()
    {
        return $this->hasMany(Account::class, 'customer_id');
    }

    public function customerCountry()
    {
        return $this->belongsTo(Country::class,'country_id');
    }

    public function customerAccountCertificates()
    {
        return $this->hasMany(AccountCertificate::class, 'customer_id', 'id');
    }
    public function latestAccount()
    {
        return $this->hasOne(Account::class)->latest('id');
    }
    public function customerKyc()
    {
        return $this->hasMany(CustomerKycs::class, 'customer_id');
    }
    public function approvedCustomerKyc()
    {
        return $this->hasMany(CustomerKycs::class, 'customer_id')->where('approval_status', 1);
    }
//    public function customerKycApproved()
//    {
//        return $this->hasOne(CustomerKycs::class, 'customer_id')->latest();
//    }
    public function customerKycApproved()
    {
        return $this->hasOne(CustomerKycs::class, 'customer_id')->where('status', CustomerKycs::TYPE_APPROVED)->latest();
    }
}
