<?php
namespace App\Services\Checkout;

use App\Constants\AppConstants;
use App\Constants\EmailConstants;
use App\Helper\Helper;
use App\Models\Orders;
use App\Services\SendGrid\SendMailService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generateInvoiceByOrderId($orderId, $type = 1)
    {
        try
        {
            // Temporary file saving path!
            $filePath = '/';
            $filePathName = 'Generated-Pdf-Invoice';

            $order = Orders::with('customer', 'account', 'account.server', 'jlPlans', 'coupon')->find($orderId);

            // Init Mpdf object
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetWatermarkImage('https://fundednext.fra1.cdn.digitaloceanspaces.com/Certificates%2Flogo-top.png', 1, [110, 110]);
            $mpdf->showWatermarkImage  = true;
            $mpdf->setAutoBottomMargin = 'stretch';
            $html = view("emails.invoice", compact('order', 'type'))->render();

            // Converting html file to PDF content here.
            $mpdf->WriteHTML($html);
            $pdfFilePath = $filePath . $filePathName . '.pdf';
            $attachment  = $mpdf->Output($pdfFilePath, 'S');

            // Saving the file temporarily in local storage
            Storage::disk('local')->put($pdfFilePath, $attachment, 'public');
            $filePathOrigin = storage_path("app/".$filePathName . '.pdf');

            // Getting dependency data to send email via send grid.
            $getEmailData = $this->getExternalData($order, $type);
            $service = new SendMailService(
                EmailConstants::SENDGRID_API_KEY,
                $getEmailData['template'],
                EmailConstants::FROM_EMAIL,
                EmailConstants::FROM_NAME,
                $order->customer->email,
                Helper::getOnlyCustomerName($order->customer->name),
                $getEmailData['body'],
                $filePathOrigin
            );

            // Sending mail with invoice file with checkings.
            if ($service->sendWithFile($getEmailData['filename']) == false)
            {
                throw new \Exception("Error to send invoice via email service!");
            }

            // Removing temporary file after operation successful.
            unlink($filePathOrigin);
            return true;
        }
        catch(\Exception $e)
        {
            Log::error("Error in ".self::class." -> generateInvoiceByOrderId. ".$e->getMessage());
            return false;
        }
    }

    private function getExternalData($order, $type)
    {
        // Checking if the invoice generation type is 1
        if ($type == AppConstants::PRODUCT_ORDER_NEW_ACCOUNT)
        {
            // Setup the email template and body and the attachment file name.
            $emailTemplate = EmailConstants::NEW_SUBSCRIPTION_MAIL;
            $emailBody = [
                "name" => Helper::getOnlyCustomerName($order->customer->name),
                "plan" => $order->jlPlans->name,
                "mt4_login_id"=> $order->account->login,
                "mt4_login_password" => $order->account->password,
                "mt4_server_id" => $order->account->server->friendly_name,
                "trustpilot_url" => EmailConstants::TRUSTPILOT_URL
            ];
            $emailFileName = "Subscription-invoice.pdf";
        }
        else if ($type == AppConstants::PRODUCT_ORDER_TOPUP)
        {
            // Setup the email template and body and the attachment file name.
            $emailTemplate = EmailConstants::TOP_UP_REQUEST_MAIL_ACCEPTED;
            $emailBody = [
                "name" => Helper::getOnlyCustomerName($order->customer->name),
                "login_id"=> $order->account->login,
                "topup_request_date" => date('Y-m-d') . " " . config('app.timezone_utc')
            ];
            $emailFileName = "TopUp-invoice.pdf";
        }
        else if ($type == AppConstants::PRODUCT_ORDER_RESET)
        {
            // Setup the email template and body and the attachment file name.
            $emailTemplate = EmailConstants::RESET_REQUEST_MAIL_ACCEPTED;
            $emailBody = [
                "name" => Helper::getOnlyCustomerName($order->customer->name),
                "login_id"=> $order->account->login,
                "reset_request_date" => date('Y-m-d') . " " . config('app.timezone_utc')
            ];
            $emailFileName = "Reset-invoice.pdf";
        }
        else
        {
            // Setup the email template and body and the attachment file name.
            $emailTemplate = EmailConstants::NEW_SUBSCRIPTION_MAIL;
            $emailBody = [
                "name" => Helper::getOnlyCustomerName($order->customer->name),
                "plan" => $order->jlPlans->name,
                "mt4_login_id"=> $order->account->login,
                "mt4_login_password" => $order->account->password,
                "mt4_server_id" => $order->account->server->friendly_name,
                "trustpilot_url" => EmailConstants::TRUSTPILOT_URL
            ];
            $emailFileName = "Subscription-invoice.pdf";
        }

        return [
            "template"  => $emailTemplate,
            "body"  => $emailBody,
            "filename"  => $emailFileName
        ];
    }
}
