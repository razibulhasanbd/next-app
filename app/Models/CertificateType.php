<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificateType extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $table = 'certificate_types';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'label',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function typeCeritificates()
    {
        return $this->hasMany(Ceritificate::class, 'type_id', 'id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
