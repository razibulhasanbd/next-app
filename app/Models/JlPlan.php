<?php

namespace App\Models;

use App\Constants\AppConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JlPlan extends Model
{
    use HasFactory;

    protected $connection = AppConstants::DATABASE_CONNECTION_FUNDED_NEXT_BACKEND;

    protected $table = "plans";
}
