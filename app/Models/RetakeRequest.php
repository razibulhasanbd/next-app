<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetakeRequest extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }


    
}
