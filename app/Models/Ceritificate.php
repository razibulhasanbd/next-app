<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ceritificate extends Model
{
    use SoftDeletes;
   // use Auditable;
    use HasFactory;

    public $table = 'certificates';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'html_markup',
        'demo_image',
        'type_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function certificateAccountCertificates()
    {
        return $this->hasMany(AccountCertificate::class, 'certificate_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(CertificateType::class, 'type_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
