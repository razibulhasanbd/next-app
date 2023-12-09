<?php

namespace App\Console\Commands;

use App\Constants\CommandConstants;
use Illuminate\Console\Command;
use App\Services\AccountService;
use App\Http\Controllers\ScheduledJobsController;
use Illuminate\Support\Facades\Log;

class MtinitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = CommandConstants::ScheduleJobs_Mtinit;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mt4 server initialization';

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
        $accountService     = new AccountService();
        $scheduleController = new ScheduledJobsController($accountService);
        $scheduleController->mtinit();
        Log::info('Mt4 server initialization done');
    }
}
