<?php

namespace App\Console\Commands;

use App\Constants\AppConstants;
use App\Constants\CommandConstants;
use App\Jobs\OneSignalNotificationSendingJob;
use App\Models\NewsCalendar;
use App\Services\Notification\OneSignal\OneSignalService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Str;


class PushNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = CommandConstants::News_Calendar_Push_Notification_To_All;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try{

            $news = $this->getUpcomingNews();


            foreach ($news as $n){
                $message= "Event: {$n->title} Currency : {$n->country} Date & Time : {$n->date} (Server Time)";
                OneSignalService::sendNotification(message:$message,headings: 'Restricted Trade',subtitle: 'FundedNext',url: "https://fundednext.com");
            }


        }catch (Exception $exception){
            dd('failed--',$exception);
        }
    }

    public function getUpcomingNews()
    {
        return NewsCalendar::where('is_restricted', 1)
            ->where('date', Carbon::now()->addMinutes(config('onesignal.notification_time_before_minutes'))->format('Y-m-d H:i'))
            ->orWhere('date', Carbon::now()->subMinutes(config('onesignal.notification_time_after_minutes'))->format('Y-m-d H:i'))
            ->get();

    }
}
