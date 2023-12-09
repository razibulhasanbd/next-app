<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AccountStatusMessage extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;
    use Auditable;
    use HasFactory;

    public $table = 'account_status_messages';

    /** this const variable's value is the same as account_status_messages table ID. If you add new more entity in account_status_messages table, you will have to create new const here also */
    public const DAILY_LOSS_LIMIT                           = 1;
    public const MONTHLY_LOSS_LIMIT                         = 2;
    public const ACCOUNT_RESET_TOPUP                        = 3;
    public const MONTH_ENDED_IN_LOSS_MTD_FULFILLED          = 4;
    public const MONTH_END_LOSS_MTD_NOT_FULFILLED           = 5;
    public const MONTH_END_PARTIAL_PROFIT_MTD_NOT_FULFILLED = 6;
    public const MONTH_END_PARTIAL_PROFIT_MTD_FULFILLED     = 7;
    public const PROFIT_TARGET_REACHED                      = 8;
    public const ADMIN_PAUSED                               = 9;
    public const APPROVED_FROM_TRA                          = 10;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'message',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
