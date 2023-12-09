<?php

namespace App\Http\Requests;

use App\Models\TraderGame;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTraderGameRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('trader_game_edit');
    }

    public function rules()
    {
        return [
            'dashboard_user' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'pnl' => [
                'numeric',
            ],
            'mental_score' => [
                'string',
                'nullable',
            ],
            'tactical_score' => [
                'string',
                'nullable',
            ],
        ];
    }
}
