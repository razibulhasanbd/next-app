<?php

namespace App\Console\Commands;

use App\Models\Plan;
use Illuminate\Console\Command;
use App\Constants\CommandConstants;
use App\Helper\Helper;
use Exception;
use Illuminate\Support\Facades\Log;

class UpdatePlanDuration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature =  CommandConstants::PlanUpdateDuration;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =  'Update plan duration';

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
        try {
            if(is_numeric($this->argument('duration'))){
                $updatedData = Plan::where('type', 'Evaluation P1')->update(['duration' => $this->argument('duration')]);

                // $updatedData = Plan::where('type', 'Express Demo')->update(['duration' => $this->argument('duration')]);

                $this->info('Plan duration is updated to ' . $this->argument('duration') . " days. Total updated rows: $updatedData");
                Helper::discordAlert('**Plan duration is updated** to ' . $this->argument('duration') . " days . Total updated rows: $updatedData");
            }
            else{
                $this->alert("Duration must be number");
            }

        } catch (Exception $exception) {
            Log::error("Plan duration Update Error", [$exception]);
            Helper::discordAlert("**Plan duration Update Error: **". $exception->getMessage());
            $this->error("Internal server error");
        }

    }
}
