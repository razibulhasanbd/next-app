<?php

namespace App\Http\Requests;

use App\Models\ExtendCycleLog;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyExtendCycleLogRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('extend_cycle_log_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:extend_cycle_logs,id',
        ];
    }
}
