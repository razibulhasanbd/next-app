<?php

namespace App\Http\Requests;

use App\Models\TradeSlTp;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTradeSlTpRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('trade_sl_tp_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:trade_sl_tps,id',
        ];
    }
}
