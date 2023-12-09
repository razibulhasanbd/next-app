<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Constants\EmailConstants;
use App\Helper\Helper;
use App\Jobs\EmailJob;
use App\Jobs\SendEmailJob;
use Exception;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Services\PasswordService;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Log;
use App\Services\SendGrid\SendMailService;
use App\Traits\Auditable;

class InvestorPasswordController extends Controller
{
    use Auditable;
    public function getInvestorPassword(Request $request, PasswordService $passwordService)
    {
        try {
            $account = Account::find($request->account_id);
            if (!$account) {
                return  ResponseService::apiResponse(404, 'Account not found');
            }

            if (!$account->investor_password) {
                $model = ['properties' => ['login' => $account->login]];
                $model = json_encode($model);
                $model = json_decode($model);
                $this->audit("investor_password:generated", $model);
                $passwordService->changeInvestorPassword($account);
            }

            return  ResponseService::apiResponse(
                200,
                'Investor password retrieved',
                [
                    'investor_password' => $passwordService->getInvestorPassword($account)
                ]
            );
        } catch (Exception $exception) {
            Log::error($exception);
            return  ResponseService::apiResponse(500, 'Internal server error');
        }
    }

    public function setInvestorPassword(Request $request, PasswordService $passwordService)
    {
        try {
            $account = Account::find($request->account_id);
            if (!$account) {
                return  ResponseService::apiResponse(404, 'Account not found');
            }
            $passwordService->changeInvestorPassword($account);
            return  ResponseService::apiResponse(200, 'Successfully changed investor password', ['investor_password' => $passwordService->getInvestorPassword($account)]);
        } catch (Exception $exception) {
            Log::error($exception);
            return  ResponseService::apiResponse(500, 'Internal server error');
        }
    }

    function sendInvestorPasswordResetEmail(Request $request)
    {
        try {
            $account = Account::with('customer')->find($request->account_id);
            // Email already triggering from set method
            // $details = [
            //     'template_id' => EmailConstants::INVESTOR_PASSWORD_GENERATED,
            //     'to_name'     => Helper::getOnlyCustomerName($account->customer->name),
            //     'to_email'    => $account->customer->email,
            //     'email_body'  => ['name' => Helper::getOnlyCustomerName($account->customer->name), 'mt4_login_id' => $account->login]
            // ];
            // EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);

            return ResponseService::apiResponse(200, 'Email sent successfully');
        } catch (Exception $exception) {
            Log::error($exception);
            return ResponseService::apiResponse(500, 'Internal server error');
        }
    }
}
