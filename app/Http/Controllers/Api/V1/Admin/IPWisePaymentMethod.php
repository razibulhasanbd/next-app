<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Constants\AppConstants;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Services\PaymentMethodService;
use App\Services\ResponseService;
use App\Services\Stripe\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class IPWisePaymentMethod extends Controller
{
    public $paymentMethodService;


    public function __construct()
    {

        $this->paymentMethodService = new PaymentMethodService();
    }


    public function paymentMethod(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'country_code'    => 'required',
            ]);

            if ($validator->fails()) {
                return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
            }

            $data = $this->paymentMethodService->paymentList($request->country_code);

            return ResponseService::apiResponse(200, 'success',$data );


        }catch (\Exception $exception){


            Log::error("IPWisePaymentMethod::paymentMethod()", [$exception]);
            return ResponseService::apiResponse(500, "Internal server error");
        }

    }

}
