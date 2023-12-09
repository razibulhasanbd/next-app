<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use function PHPUnit\Framework\isNull;

class Trade extends Model
{
    use SoftDeletes;
    //use Auditable;
    use HasFactory;

    public $table = 'trades';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'account_id',
        'close_price',
        'close_time',
        'close_time_str',
        'commission',
        'digits',
        'login',
        'lots',
        'open_price',
        'open_time',
        'open_time_str',
        'pips',
        'profit',
        'reason',
        'sl',
        'state',
        'swap',
        'symbol',
        'ticket',
        'tp',
        'type',
        'type_str',
        'volume',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function tradeSlTp()
    {
        return $this->hasMany(TradeSlTp::class);
    }
    public function tradeSlFirst() {
        return $this->hasOne(TradeSlTp::class)->where('type','1');
    }

    public function tradeTPFirst() {
        return $this->hasOne(TradeSlTp::class)->where('type','2');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id')->with('plan.package');
    }


    /**
     * filter trade data
     *
     * @param string|int $ticket
     * @param string|int $accountId
     * @return mixed
     */
    public static function whereTicket($ticket, $accountId = null){
        if($accountId == null){
            return self::where("ticket", $ticket);
        }
        return self::where("ticket", $ticket)->where('account_id', $accountId);
    }
}
