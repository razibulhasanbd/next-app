<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;

class FixServerId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:serverid';

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
        $accounts = Account::all();

        foreach ($accounts as $account) {


            if (str_starts_with($account->login, '20')) {
                $account->server_id = 1;
                $account->save();
            } else if (str_starts_with($account->login, '888')) {
                $account->server_id = 2;
                $account->save();
            }else if (str_starts_with($account->login, '199')) {
                $account->server_id = 3;
                $account->save();
            }
            else if (str_starts_with($account->login, '90')) {
                $account->server_id = 4;
                $account->save();
            }
        }
    }
}
