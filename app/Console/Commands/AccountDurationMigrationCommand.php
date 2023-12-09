<?php

namespace App\Console\Commands;
use App\Models\Account;
use Illuminate\Console\Command;
use Batch;
use Illuminate\Support\Facades\Log;

class AccountDurationMigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:duration-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All account durations from account plan duration table';

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
        Account::with('plan')->chunk(500, function ($accounts) {
            $updateData = [];
            foreach ($accounts as $account) {
                $updateData[] = [
                    'id'               => $account->id,
                    'duration' => $account->plan->duration
                ];
            }
            if (sizeof($updateData)) {
                Batch::update(new Account, $updateData, "id");
                Log::info("The chunk data update is done");
            }
        });

        Log::info("All update is done");
    }
}
