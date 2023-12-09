<?php

namespace App\Http\Requests;

use App\Models\TradeSlTp;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTradeSlTpRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('trade_sl_tp_create');
    }

    public function rules()
    {
        return [
            'trade_id' => [
                'required',
                'integer',
            ],
            'type' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'value' => [
                'string',
                'required',
            ],
        ];
    }
}
