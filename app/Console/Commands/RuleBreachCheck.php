<?php

namespace App\Console\Commands;

use App\Constants\CommandConstants;
use Illuminate\Console\Command;
use App\Services\AccountService;
use App\Services\RuleBreachService;
use App\Http\Controllers\RuleBreachJobController;

class RuleBreachCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = CommandConstants::Rule_Breach_Check;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for rule breach';

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

        $accountService    = new AccountService();
        $ruleBreachService = new RuleBreachService($accountService);
        $controller        = new RuleBreachJobController();
        // $controller->rulesCheckerV2();
        $controller->rulesCheckerV3($ruleBreachService);
        return Command::SUCCESS;
    }
}
