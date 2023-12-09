<?php

namespace App\Http\Requests;

use App\Models\NewsCalendar;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateNewsCalendarRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('news_calendar_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
            'country' => [
                'string',
                'required',
            ],
            'date' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'impact' => [
                'string',
                'required',
            ],
            'forecast' => [
                'string',
                'required',
            ],
            'previous' => [
                'string',
                'required',
            ],
            'is_restricted' => [
                'required',
            ],
        ];
    }
}
