<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UtilityItem extends Model
{
    use SoftDeletes;
    use HasFactory;

    public const STATUS_SELECT = [
        1 => 'Active',
        2 => 'Inactive',
    ];

    public $table = 'utility_items';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'utility_category_id',
        'icon_url',
        'header',
        'description',
        'download_file_url',
        'youtube_embedded_url',
        'youtube_thumbnail_url',
        'status',
        'order_value',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function utility_category()
    {
        return $this->belongsTo(UtilityCategory::class, 'utility_category_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
