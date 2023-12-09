<?php

namespace App\Jobs;

use App\Services\SendGrid\SendMailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $details;
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $service = new SendMailService(
            env("SENDGRID_API_KEY"),
            $this->details["template_id"],
            "notification@fundednext.com",
            "FundedNext",
            $this->details["email"],
            $this->details["name"],
            [

                "name" => $this->details["name"],
                "login_id" => $this->details["login_id"],
                "date" => array_key_exists('date', $this->details) ? $this->details["date"] : '',
            ]
        );
        $service->send();
    }
}
