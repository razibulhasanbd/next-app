<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\NewsCalendar;
use Illuminate\Console\Command;
use App\Constants\CommandConstants;

class NewsTimezone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = CommandConstants::News_Calendar_TimeZone_Change;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change timezone of all news to UTC';

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

        $allNews=NewsCalendar::all();


        foreach($allNews as $news){
            $news->date=Carbon::parse($news->date,'-0400')->setTimezone('UTC')->format('Y-m-d H:i:s');
            $news->save();
        }

        return 0;
    }
}
