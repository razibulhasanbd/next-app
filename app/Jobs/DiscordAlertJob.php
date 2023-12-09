<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Log;

class DiscordAlertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $message;
    public $customerTagAlert;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message,$customerTagAlert)
    {
        $this->message = $message;
        $this->customerTagAlert = $customerTagAlert;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->customerTagAlert == true){
            $url = env('DISCORD_ALERT_CUSTOMER_TAG');
            $avatarUrl = 'https://previews.123rf.com/images/mykub/mykub1902/mykub190200410/117044254-the-attention-icon-danger-symbol-alert-icon.jpg';
            $response = Http::post($url, [
                "tts" => false,
                'username' => "Abuser Alert",
                'avatar_url' => $avatarUrl,
                'content' => $this->message,
                'Content-type: application/json',
            ]);
        }else{
            $url = env('DISCORD_ALERT');
            $avatarUrl = 'https://previews.123rf.com/images/mykub/mykub1902/mykub190200410/117044254-the-attention-icon-danger-symbol-alert-icon.jpg';
            $response = Http::post($url, [
                "tts" => false,
                'username' => "Fundednext Alert",
                'avatar_url' => $avatarUrl,
                'content' => $this->message,
                'Content-type: application/json',
            ]);
        }

        if(!$response->successful())
        {
            Log::error('Discord Alert Failed: ' . $response->body());
        }
    }
}
