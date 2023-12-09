<?php

namespace App\Console\Commands;

use App\Constants\CommandConstants;
use App\Services\AccountService;
use Illuminate\Console\Command;

class ServerPing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = CommandConstants::Server_Ping;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for server ping';

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

        $accountService = new AccountService();
        $controller = new \App\Http\Controllers\ScheduledJobsController($accountService);
        $controller->ping();
        return Command::SUCCESS;
    }
}
