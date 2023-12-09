<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Constants\AppConstants;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Customer;
use App\Services\Checkout\CheckoutService;
use App\Services\ResponseService;
use App\Services\VeriffService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KycVeriffWebhookController extends Controller
{
    public function veriffWebhook(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
//                'customer_id' => 'nullable|integer',
//                'kyc_response'    => 'required'
            ]);

            if ($validator->fails()) {
                return (new ResponseService)->apiResponse(422, 'Invalid input', $validator->errors());
            }
            (new VeriffService)->veriffWebhook($request);

        } catch (Exception $exception) {
            Log::error("veriff exception", [$exception]);
            return ResponseService::apiResponse(500, "Internal server error");
        }
    }

    public function verificationStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'veriff_id' => 'required'
            ]);

            if ($validator->fails()) {
                return (new ResponseService)->apiResponse(422, 'Invalid input', $validator->errors());
            }
            $customer = getAuthCustomer(true);
            $result = (new VeriffService)->verificationStatus($request->veriff_id, $customer->email);
            if ($result) {
                return (new ResponseService)->apiResponse(200, 'successfully approved', ['status' => true, 'customer_info' => $result]);
            } else {
                return (new ResponseService)->apiResponse(200, 'Sorry, but your document has not been approved. Please try submitting it again or contact the live chat for further assistance.', ['status' => false]);
            }

        } catch (Exception $exception) {
            Log::error("veriff verification exception", [$exception]);
            return ResponseService::apiResponse(500, "Internal server error");
        }

    }

    public function kycPDFGeneration()
    {
        $account = Account::with('customer')->where('login', "1820775380")->first();
        $result  = (new VeriffService)->generatePdf($account, 'imam h');
        dd($result);
    }

    public function kycAgreementSubmit(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'veriff_id'      => 'nullable',
                'login'          => 'required|exists:accounts,login',
                'user_agreement' => 'required|in:0,1',
                'customer_name'  => 'required|string|max:255',
                'address'  => 'required|string|max:355',
            ]);


            if ($validator->fails()) {
                return (new ResponseService)->apiResponse(422, 'Invalid input', $validator->errors());
            }
            $veriff_id = $request->veriff_id;
            if(!$veriff_id){
                $veriff_id = 'system-s-'.uniqid();
            }
            $result = (new VeriffService)->agreementSubmit($veriff_id, $request->login, $request->user_agreement, $request->customer_name, $request->address);
            if ($result) {
                return (new ResponseService)->apiResponse(200, 'Your documents have been submitted successfully and are currently under review. Our compliance team will get back to you within 48 hours', ['status' => true]);
            } else {
                return (new ResponseService)->apiResponse(200, 'Your document is not submitted, Please try again or contact with live chat', ['status' => false]);
            }

        } catch (Exception $exception) {
            Log::error("agreement submission exception", [$exception]);
            return ResponseService::apiResponse(500, "Internal server error");
        }
    }


}
