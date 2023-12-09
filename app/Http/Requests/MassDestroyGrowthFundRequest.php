<?php

namespace App\Http\Requests;

use App\Models\GrowthFund;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyGrowthFundRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('growth_fund_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:growth_funds,id',
        ];
    }
}
