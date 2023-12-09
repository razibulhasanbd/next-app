<?php

namespace App\Http\Requests;

use App\Models\Trade;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTradeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('trade_create');
    }

    public function rules()
    {
        return [
            'account_id' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'close_price' => [
                'numeric',
                'required',
            ],
            'close_time' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'close_time_str' => [
                'string',
                'required',
            ],
            'commission' => [
                'numeric',
                'required',
            ],
            'digits' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'login' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'lots' => [
                'numeric',
                'required',
            ],
            'open_price' => [
                'numeric',
                'required',
            ],
            'open_time' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'open_time_str' => [
                'string',
                'required',
            ],
            'pips' => [
                'numeric',
                'required',
            ],
            'profit' => [
                'numeric',
                'required',
            ],
            'reason' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'sl' => [
                'numeric',
                'required',
            ],
            'state' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'swap' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'symbol' => [
                'string',
                'required',
            ],
            'ticket' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'unique:trades,ticket',
            ],
            'tp' => [
                'numeric',
                'required',
            ],
            'type' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'type_str' => [
                'string',
                'required',
            ],
            'volume' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
