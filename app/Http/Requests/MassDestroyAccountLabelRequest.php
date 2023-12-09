<?php

namespace App\Http\Requests;

use App\Models\AccountLabel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAccountLabelRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('account_label_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:account_labels,id',
        ];
    }
}
