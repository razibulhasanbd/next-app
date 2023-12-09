<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentMethodsResource extends ResourceCollection
{

    public function toArray($request)
    {
        return PaymentMethodResource::collection($this->collection);
    }
}
