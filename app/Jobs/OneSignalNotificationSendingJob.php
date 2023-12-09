<?php

namespace App\Jobs;

use App\Services\Notification\OneSignal\OneSignalService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class OneSignalNotificationSendingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userIds;
    public $message;
    public $data;
    public $url;
    public $buttons;
    public $schedule;
    public $headings;
    public $subtitle;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
         $message,
         $userIds = null,
         $data = null,
         $url = null,
         $buttons = null,
         $schedule = null,
         $headings = null,
         $subtitle = null
        )
    {

       $this->userIds = $userIds;
       $this->message = $message;
       $this->data = $data;
       $this->buttons = $buttons;
       $this->url = $url;
       $this->schedule = $schedule;
       $this->headings = $headings;
       $this->subtitle = $subtitle;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if($this->userIds){
            $this->singleUserPush();
        }else{
            $this->allUserPush();
        }

    }

    /**
     * @return void
     */
    public function singleUserPush()
    {
        try {

            $this->userIds = !is_array($this->userIds) ? [$this->userIds] : $this->userIds;

            foreach (array_chunk($this->userIds,2000) as $batch){


                $response = (new OneSignalService())->sendNotificationToExternalUser($this->message,$batch, $this->url, $this->data, $this->buttons, $this->schedule, $this->headings, $this->subtitle);
                Log::info('Single User ===' . json_encode($response));

                //Save to DB
                (new OneSignalService())->storePushNotificationByBatch($batch, $this->message);
            }

        }catch (Exception $exception) {

            Log::error("OneSignalNotificationSendingJob::singleUserPush() -- " . json_encode($exception->getMessage()));
        }

    }
    public function allUserPush()
    {
        try {

            //TODO:: Save notification to DB

            $response = (new OneSignalService())->sendNotificationToAll(
                $this->message , $this->url , $this->data, $this->buttons, $this->schedule, $this->headings , $this->subtitle);
            Log::info('allUserPush == ' . json_encode($response));
        }catch (Exception $exception) {

            Log::error("OneSignalNotificationSendingJob::allUserPush() -- " . json_encode($exception->getMessage()));
        }

    }

}
