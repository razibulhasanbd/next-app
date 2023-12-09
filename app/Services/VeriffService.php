<?php

namespace App\Services;


use App\Models\Plan;
use App\Helper\Helper;
use Exception;
use App\Models\Account;
use Mpdf\MpdfException;
use App\Models\Customer;
use App\Models\CustomerKycs;
use App\Constants\AppConstants;
use App\Constants\EmailConstants;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Jobs\EmailJob;

class VeriffService
{
    /**
     * the webhook call from veriff
     * @param $request
     * @return void
     * @throws MpdfException
     */
    public function veriffWebhook($request): void
    {
        try {
            Log::info($request);
            $vendorData        = json_decode($request['verification']['vendorData']) ?? null;
            if(!$vendorData){
                Log::warning("manual verified");
                return;
            }
            $login             = $vendorData->login;
            $account           = Account::with('customer.customerCountry')->where('login', $login)->first();

            $kyc               = new CustomerKycs();
            $kyc->customer_id  = $account->customer_id ?? null;
            $kyc->account_id   = $account->id ?? null;
            $kyc->veriff_id    = $request['verification']['id'] ?? null;
            $kyc->kyc_response = json_encode($request->all());
            $kyc->status       = $request['verification']['status'] ?? $request->action;
            $kyc->save();
        } catch (Exception $exception) {
            Log::error("veriff webhook request error: ", [$exception]);
        }
    }


    /**
     * @param string $veriff_id
     * @param string $email
     * @return mixed
     */
    public function verificationStatus(string $veriff_id, string $email)
    {
        $customer = Customer::with('customerCountry')->where("email", $email)->first();
        $kyc = CustomerKycs::where('veriff_id', $veriff_id)->where('customer_id', $customer->id)->where('status', AppConstants::KYC_APPROVED)->first();
        if ($kyc) {
            return [
                'name'    => $customer->name ?? null,
                'address' => $customer->address ?? null,
                'zip'     => $customer->zip ?? null,
                'city'    => $customer->city ?? null,
                'country' => $customer->customerCountry->name ?? null,
                'phone'   => $kycInfo->customer->phone ?? null,
            ];
        }
        return [];


    }


    /**
     * @param $account
     * @param string $customer_name
     * @param string $address
     * @return string
     * @throws MpdfException
     */
    public function generatePdf($account, string $customer_name, string $address)
    {
        $type     = explode(' ', $account->type);
        $type     = $type[0] ?? null;
        $docId    = generateUniqueId();
        $kycInfo = $this->kycInfo($account->customer_id);
        $filePath = 'agreement/' . date("Y") . "/" . date("m") . "/";;
        $filePathName = 'kyc@' . $type . $docId;

        $mpdf = new \Mpdf\Mpdf(
            [
                'tempDir' => storage_path('tempdir'),
                'format'  => 'Legal'
            ]
        );

        $mpdf->SetWatermarkImage('https://fundednext.fra1.cdn.digitaloceanspaces.com/Certificates%2Flogo-top.png', 1,
            array(110, 110)
        );
        $mpdf->showWatermarkImage  = true;
        $mpdf->setAutoBottomMargin = 'stretch';
        $html                      = view("admin.kyc.agreement", compact('account', 'type', 'customer_name', 'address', 'kycInfo'))->render();
        $mpdf->SetHTMLFooter(' <div style="width: 30%;float: left"><img src="https://fundednext.fra1.cdn.digitaloceanspaces.com/Certificates%2FLogo_Main.png"></div> <div style="width: 50%; float: right; color:#2000f0;text-align: right"> <h3>OUR FUND YOUR PROFIT</h3></div>');
        $mpdf->WriteHTML($html);
        $pdfFilePath = $filePath . $filePathName . '.pdf';
        $file        = $mpdf->Output($pdfFilePath, 'S');

        Storage::disk('utility-files')->put($pdfFilePath, $file, 'public');
        return env('DO_URL') . $pdfFilePath;

    }

    /**
     * aggrement file store and delete old file
     * @param string $veriff_id
     * @param int $login
     * @param int $user_agreement
     * @param string $customer_name
     * @return bool
     * @throws MpdfException
     */
    public function agreementSubmit(string $veriff_id, int $login, int $user_agreement, string $customer_name, string $address): bool
    {
        $account                = Account::with('customer.customerCountry')->where('login', $login)->first();
        $generatedAgreementFile = self::generatePdf($account, $customer_name, $address);

        $data = [
            'status'       => 'success',
            "verification" => [
                "id"         => $veriff_id,
                "person"     => [
                    'firstName' => $customer_name,
                    'addresses' => ['fullAddress'=> $address]
                ],
                "status"     => 'approved',
                "vendorData" => json_encode(['email'=> $account->customer->email, 'login'=> $account->login]),
            ]
        ];

        if ($generatedAgreementFile) {
            $customer_id = $account->customer->id ?? null;
            $account_id = $account->id ?? null;
            $kyc = CustomerKycs::where('customer_id', $customer_id)->where('account_id', $account_id)->where('user_agreement', 1)->first();
            if(!$kyc){
                $kyc                 = new CustomerKycs();
            }

            $kyc->customer_id    = $account->customer->id ?? null;
            $kyc->account_id    = $account->id ?? null;
            $kyc->veriff_id      = $veriff_id;
            $kyc->kyc_response   = json_encode($data);
            $kyc->status         = AppConstants::KYC_APPROVED;
            $kyc->pdf_path       = $generatedAgreementFile;
            $kyc->user_agreement = $user_agreement;
            $kyc->save();
            //TODO:: SEND email sequence with attached file

            $details = [
                'template_id'          => EmailConstants::KYC_APPROVAL_MAIL,
                'to_name'              => Helper::getOnlyCustomerName($account->customer->name),
                'to_email'             => Helper::getOnlyCustomerName($account->customer->email),
                'email_body' => [
                    "name" => $account->customer->name,
                    "login" => $account->login,
                    "approval_link" => $kyc->pdf_path,
                    ]
            ];
            EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
            return true;
        }
        return false;
    }

    /**
     * @param $customer_id
     * @return mixed
     */
    private function kycInfo($customer_id)
    {
        return CustomerKycs::where('customer_id', $customer_id)
            ->where('status', AppConstants::KYC_APPROVED)
            ->first();
    }


}
