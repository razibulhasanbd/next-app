<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;
    //use Auditable;
    use HasFactory;

    public $table = 'packages';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function planName()
    {
        return $this->hasMany(Plan::class, 'package_id');
    }
    public function accounts()
    {
        $accounts=[];
        $this->planName()->each(function($plan,$key)use (&$accounts){
            
            array_push($accounts,...$plan->accounts);

        });
        return collect($accounts);


    }
}
