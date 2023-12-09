<?php

namespace App\Console\Commands;

use App\Models\Plan;
use App\Models\Account;
use App\Models\PlanRule;
use Illuminate\Console\Command;

class SyncPlanRules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:plan-rules';

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

        $this->info('Mapping plans...');
        $plans = Plan::where('id', '>', 23)->get();

        $mappedPlans = [];
        foreach ($plans as $newPlan) {
            $ogPlan = Plan::where('type', '=', $newPlan->type)->where('startingBalance', $newPlan->startingBalance)->first();

            $newPlanRules = $newPlan->rules;

            foreach ($newPlanRules as $newRule) {


                $check = PlanRule::updateOrCreate([
                    'plan_id'   => $ogPlan->id,
                    'rule_name_id'   => $newRule->id,
                ], [
                    'value'     =>  $newRule->pivot->value,
                ]);
            }

            $mappedPlans[$newPlan->id] = $ogPlan->id;


            $this->info("From plan: " . $newPlan->id . " -->> To plan: " . $ogPlan->id);
        }

        return 'done';
    }
}
