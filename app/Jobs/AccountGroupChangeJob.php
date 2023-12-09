<?php

namespace App\Jobs;

use App\Models\Account;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AccountGroupChangeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $account;
    public $groupName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Account $account, string $groupName)
    {
        $this->account   = $account;
        $this->groupName = $groupName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $token    = $this->account->server->login;
            $array    = [
                'login'    => $this->account->login,
                'password' => $this->account->password,
                'group'    => $this->groupName,
            ];

            $response = Http::post($this->account->server->url . "/user/update?token=$token", $array);

            if ($response->ok()) {
                Log::info($this->account->login ." Group change to ". $this->groupName);
            }
            else{
                Log::alert($this->account->login . " can not change group to " . $this->groupName, [$response]);
                $this->fail();
            }

        } catch (Exception $exception) {
            Log::error($exception);
        }
    }
}
