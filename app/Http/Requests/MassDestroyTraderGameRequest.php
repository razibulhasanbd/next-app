<?php

namespace App\Http\Requests;

use App\Models\TraderGame;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTraderGameRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('trader_game_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:trader_games,id',
        ];
    }
}
