<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Constants\EmailConstants;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\SendGrid\SendMailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class EmailJobWithFile implements ShouldQueue
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
            EmailConstants::SENDGRID_API_KEY,
            $this->details["template_id"],
            EmailConstants::FROM_EMAIL,
            EmailConstants::FROM_NAME,
            $this->details["to_email"],
            $this->details["to_name"],
            $this->details["email_body"],
            $this->details["attachment_file"],
        );
        $service->sendWithFile();
    }
}
