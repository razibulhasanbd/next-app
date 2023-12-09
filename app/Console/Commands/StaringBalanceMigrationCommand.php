<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;
use Batch;
use Illuminate\Support\Facades\Log;

class StaringBalanceMigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:staring-balance-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Account starting balance change';

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
        // Account::with('plan')->chunk(500, function ($accounts) {
        //     $updateData = [];
        //     foreach ($accounts as $account) {
        //         $updateData[] = [
        //             'id'               => $account->id,
        //             'starting_balance' => $account->plan->startingBalance
        //         ];
        //     }
        //     if (sizeof($updateData)) {
        //         Batch::update(new Account, $updateData, "id");
        //         Log::info("The chunk data update is done");
        //     }
        // });

        // Log::info("All update is done");
    }
}
