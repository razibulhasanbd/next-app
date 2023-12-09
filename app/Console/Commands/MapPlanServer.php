<?php

namespace App\Console\Commands;

use App\Models\Plan;
use App\Models\Account;
use Illuminate\Console\Command;

class MapPlanServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'map-plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Map new plans to original plans';

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
        $this->info('Mapping plans...');
        $plans = Plan::where('id', '>', 23)->get();

        $mappedPlans = [];
        foreach ($plans as $newPlan) {
            $ogPlan = Plan::where('type', '=', $newPlan->type)->where('startingBalance', $newPlan->startingBalance)->first();

            $mappedPlans[$newPlan->id] = $ogPlan->id;


            $this->info("From plan: " . $newPlan->id . " -->> To plan: " . $ogPlan->id);
        }
        $this->error("Plan Mapping done!");
        $this->error("Starting Account Plan Mapping...");
        $accounts = Account::with('plan')->where('plan_id', '>', 23)->get();

        foreach ($accounts as $account) {
            $account->plan_id = $mappedPlans[$account->plan_id];
            $this->info("Account from plan_id: " . $newPlan->id . " -->> Account to plan_id: " . $ogPlan->id);

            $account->save();
        }

        return 'done';
    }
}
