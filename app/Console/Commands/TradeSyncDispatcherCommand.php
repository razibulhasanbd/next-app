<?php

namespace App\Console\Commands;

use App\Constants\CommandConstants;
use Illuminate\Console\Command;
use App\Services\AccountService;
use App\Http\Controllers\ScheduledJobsController;
use Illuminate\Support\Facades\Log;

class TradeSyncDispatcherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = CommandConstants::ScheduleJobs_TradeSyncJob;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run trade sync job without overlapping';
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
        $accountService       = new AccountService();
        $runTradeSncDispather = new ScheduledJobsController($accountService);
        $runTradeSncDispather->tradeSyncDispatcher();
        Log::info('Trade sync dispatcher done');
    }
}
