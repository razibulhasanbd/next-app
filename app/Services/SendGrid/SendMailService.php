<?php

namespace App\Services\SendGrid;

use SendGrid;
use SendGrid\Mail\To;
use SendGrid\Mail\From;
use SendGrid\Mail\Mail;
use Illuminate\Support\Facades\Log;

class SendMailService
{
    public const CONGRATULATION_ON_YOUR_FIRST_WITHDRAWL = 'd-a174e505065c48f38b8de0f04f88f9c2';
    public const ACCOUNT_GROWTH_TARGET_REACHED = 'd-5bc50d8b6c1c40c39c5aa6b313283b05';
    public const EVALUATION_PHASE_ONE_COMPLETED  = 'd-69c3e9d658bf4a6f9537d701f2423f4d';
    public const EVALUATION_PHASE_TWO_COMPLETED  = 'd-d1831b77bcd0449bb683cca3e9179072';
    public const DOCUMENT_VERIFICATION_COMPLETED=  'd-aacbe70c36a145a48e12f7c74e6e3009';
    public const CONGRATULATION_YOUR_REAL_FUNDED_ACCOUNT_IS_WATING_FOR_YOU = 'd-f955b86956164d9db52e3f10f8a7caee';
    public const A_RULE_HASBEEN_BREACHED_ON_ACCOUNT= 'd-d9e0af3e73104ff08b9ea1579a510152';
    public const Retake_Request_Accepted= 'd-30e3ceacf8364acdb9fb002477047021';

    private $api_token, $template_id, $to_email, $to_name, $data, $from_name, $from_email,$file;

    public function __construct(
        $api_token,
        $template_id,
        $from_email,
        $from_name,
        $to_email,
        $to_name,
        $data,
        $file=null
    ) {
        $this->api_token = $api_token;
        $this->template_id = $template_id;
        $this->to_email = $to_email;
        $this->to_name = $to_name;
        $this->data = $data;
        $this->from_email = $from_email;
        $this->from_name = $from_name;
        $this->file = $file;
    }

    public function send()
    {

        $from = new From($this->from_email, $this->from_name);

        $to = [
            new To(
                $this->to_email,
                $this->to_name,
                // passing the data and subject here
                $this->data,
            ),
        ];

        $email = new Mail(
            $from,
            $to
        );

        $email->setTemplateId($this->template_id);

        $sendgrid = new SendGrid($this->api_token);

        try {

            $response = $sendgrid->send($email);
            return $response;
            // Log::info(json_encode($response));
        } catch (\Exception $e) {
            Log::error(json_encode($e->getMessage()));
        }
    }

    public function sendWithFile($filename = null)
    {

        $from = new From($this->from_email, $this->from_name);

        $to = [
            new To(
                $this->to_email,
                $this->to_name,
                // passing the data and subject here
                $this->data,
            ),
        ];

        $email = new Mail(
            $from,
            $to
        );

        if($this->file != null){
            $file_encoded = base64_encode(file_get_contents($this->file));
            $email->addAttachment(
                $file_encoded,
                "application/text",
                ($filename != null)?$filename:"Subscription-invoice.pdf",
                "attachment"
            );
        }


        $email->setTemplateId($this->template_id);

        $sendgrid = new SendGrid($this->api_token);

        try {

            $response = $sendgrid->send($email);
            return $response;
            // Log::info(json_encode($response));
        } catch (\Exception $e) {
            Log::error(json_encode($e->getMessage()));
            return false;
        }
    }
}
