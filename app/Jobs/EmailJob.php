<?php

namespace App\Jobs;

use App\Constants\EmailConstants;
use App\Helper\Helper;
use App\Services\SendGrid\SendMailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EmailJob implements ShouldQueue
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
        $this->details["to_name"] = Helper::getOnlyCustomerName($this->details["to_name"]);
        $service = new SendMailService(
            EmailConstants::SENDGRID_API_KEY,
            $this->details["template_id"],
            EmailConstants::FROM_EMAIL,
            EmailConstants::FROM_NAME,
            $this->details["to_email"],
            $this->details["to_name"],
            $this->details["email_body"],
        );
        $service->send();
    }
}
