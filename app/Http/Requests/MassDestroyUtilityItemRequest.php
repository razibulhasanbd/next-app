<?php

namespace App\Http\Requests;

use App\Models\UtilityItem;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyUtilityItemRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('utility_item_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:utility_items,id',
        ];
    }
}
