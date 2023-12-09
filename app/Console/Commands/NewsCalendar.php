<?php

namespace App\Console\Commands;

use App\Constants\CommandConstants;
use Illuminate\Console\Command;

class NewsCalendar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = CommandConstants::News_Calendar;

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
       $controller = new \App\Http\Controllers\NewsController();
       return $controller->getThisWeekNews();
    }
}
