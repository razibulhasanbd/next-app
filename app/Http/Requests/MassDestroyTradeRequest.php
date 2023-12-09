<?php

namespace App\Http\Requests;

use App\Models\Trade;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTradeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('trade_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:trades,id',
        ];
    }
}
