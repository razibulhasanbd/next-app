<?php

namespace App\Http\Requests;

use App\Models\AccountMetric;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAccountMetricRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('account_metric_edit');
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
            'maxDailyLoss' => [
                'numeric',
                'required',
            ],
            'metricDate' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'isActiveTradingDay' => [
                'required',
            ],
            'trades' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'averageLosingTrade' => [
                'numeric',
                'required',
            ],
            'averageWinningTrade' => [
                'numeric',
                'required',
            ],
            'lastBalance' => [
                'numeric',
                'required',
            ],
            'lastEquity' => [
                'numeric',
                'required',
            ],
            'lastRisk' => [
                'numeric',
                'required',
            ],
            'maxMonthlyLoss' => [
                'numeric',
                'required',
            ],
        ];
    }
}
