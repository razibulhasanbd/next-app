<?php

namespace App\Console\Commands;

use App\Constants\CommandConstants;
use App\Models\Account;
use App\Models\Plan;
use App\Models\TargetReachedAccount;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Batch;
use Exception;

class OriginAccountDataMigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = CommandConstants::Origin_Account_Data_Migration_Command;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update account table parent_account_id column';


    private $planIds;
    public $basePlan;
    public $nextPlan;
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
            // $planTypes          = $this->getPlanTypeOnly();
            // $this->basePlan     = $this->choice("Please enter your base plan type number" , $planTypes);
            // $this->nextPlan     = $this->choice("Please enter your next plan type number" , $planTypes);
            $this->dataUpdate("Evaluation P1", "Evaluation P2");
            $this->dataUpdate("Evaluation P2", "Evaluation Real");
            $this->dataUpdate("Express Demo", "Express Real");
        } catch (Exception $exception) {
            $this->error ("Internal error. Error: ".$exception->getMessage());
        }

    }


    /**
     * update data
     *
     * @param string $basePlan
     * @param string $nextPlan
     * @return void
     */
    private function dataUpdate($basePlan, $nextPlan){
        $this->info("----------------------------------------------------------------------------------");
        $this->info("Fetching data for $basePlan and $nextPlan");
        $this->info("----------------------------------------------------------------------------------");

        $this->basePlan = $basePlan;
        $this->nextPlan = $nextPlan;
        $this->planIds = $this->getPlanId();
        if(sizeOf($this->planIds)){
            $updateArray = [];
            $targetReachedAccounts = TargetReachedAccount::with('account')
                                        ->whereIn('plan_id', $this->planIds)
                                        ->whereNotNull('approved_at')
                                        ->whereNull('denied_at')
                                        // ->where("account_id", 5096)
                                        // ->limit(2)
                                        ->get();

            $this->info("-----------------------------------------------------------------------------------");
            $this->info("Total: ".$targetReachedAccounts->count());
            $this->info("-----------------------------------------------------------------------------------");

            foreach ($targetReachedAccounts as $key => $targetReachedAccount) {
                $predictAccount = $this->nextAccountPredict($targetReachedAccount->account->customer_id, $targetReachedAccount->approved_at);
                if($predictAccount){
                    $updateArray[] = [
                        'id'                => $predictAccount->id,
                        'parent_account_id' => $targetReachedAccount->account_id
                    ];
                    $this->info ($key+1 .": Account ID: $predictAccount->id -> Parent ID: $targetReachedAccount->account_id");
                }
                else{
                    $this->error ($key+1 .": Predict Account not found for account ID $targetReachedAccount->account_id");
                }
            }

            echo "Updating started \n";
            Batch::update(new Account, $updateArray, "id");
            $this->info ("Successfully updated");
            echo("-----------------------------------------------------------------------------------\n");
        }
        else{
            $this->error ("Sorry, this plan type does not exist");
        }
    }


    /**
     * get plan ids based on user provided plan string
     *
     * @return array
     */
    private function getPlanId() : array{
        return Plan::where("type", $this->basePlan)->pluck('id')->toArray();
    }


    /**
     * predict next account
     *
     * @param integer $customerId
     * @param string $approvedAt
     * @return mixed
     */
    private function nextAccountPredict(int $customerId, string $approvedAt) : mixed{
        return Account::whereRelation('plan', 'type', $this->nextPlan)
                            ->where("customer_id", $customerId)
                            ->where('created_at' , '<' , Carbon::parse($approvedAt))
                            ->orderBy('created_at', 'desc')
                            ->first();
    }


}
