<?php

namespace App\Models;

use App\Models\Coupon;
use App\Models\Account;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KycAgreementLogs extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'kyc_agreement_logs';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'id',
        'kyc_id',
        'login',
        'file_path',
    ];

    public function customerKyc()
    {
        return $this->belongsTo(CustomerKycs::class, 'kyc_id');
    }
}
