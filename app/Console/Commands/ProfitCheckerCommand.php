<?php

namespace App\Console\Commands;

use App\Services\NewsService;
use Illuminate\Console\Command;
use App\Services\AccountService;
use App\Constants\CommandConstants;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ScheduledJobsController;

class ProfitCheckerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = CommandConstants::ScheduleJobs_ProfitChecker;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For profit checker and balance reset';

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
        $newsService        = new NewsService();
        $accountService     = new AccountService();
        $scheduleController = new ScheduledJobsController($accountService);
        $scheduleController->profitChecker($newsService);
        Log::info('Profit checker done');
    }
}
