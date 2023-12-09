<?php

namespace App\Jobs;

use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class AccountPlanMigrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $account;

    public $timeout = 180;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Http::retry(3, 30000)->withHeaders([
                'Accept'             => 'application/json',
                'X-Verification-Key' => env('WEBHOOK_TOKEN'),
            ])->post(env('FRONTEND_URL') . "/api/v1/webhook/account-phase-migration", [
                "accountId"        => $this->account->id,
                "phaseId"          => null,
                "createNewAccount" => true,
            ]);
        } catch (\Throwable $exception) {
            if ($this->attempts() > 3) {
                throw $exception;
            }
            $this->release(60);
            return;
        }
    }

}
