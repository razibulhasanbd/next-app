<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Mavinoo\Batch\Batch;

class CountryDataMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'country:migrate';

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
        $filePath      = public_path() . "/country-data.csv";
        $migrationData = [];
        if (($open = fopen($filePath, "r")) !== FALSE) {
            while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                if (!$data[1] || $data[1] == 'NULL') {
                    continue;
                }
                $migrationData[] = [
                    'email'      => $data[0],
                    'country_id' => (int) $data[1],
                ];
            }
            fclose($open);
        }
        $userInstance = new Customer();
        $index        = 'email';
        batch()->update($userInstance, $migrationData, $index);
    }
}
