<?php

namespace App\Services;

use App\Models\Account;
use App\Models\AccountStatusLog;
use Exception;
use Illuminate\Support\Facades\Log;

class AccountStatusLogService
{

    /**
     * store new status log of an account
     *
     * @param Account $account
     * @param integer $newStatus
     * @param integer|null $message
     * @param mixed|null $data
     * @return void
     */
    public static function create(Account $account, int $newStatus, int $message = null, mixed $data = null)
    {
        try {
            $currentStatus = AccountStatusLogService::currentStatus($account);
            AccountStatusLog::create([
                "account_id"                => $account->id,
                "old_status_id"             => $currentStatus ? $currentStatus->new_status_id : null,
                "new_status_id"             => $newStatus,
                "account_status_message_id" => $message,
                "data"                      => json_encode($data),
            ]);
        } catch (Exception $exception) {
            Log::error($exception);
        }
    }


    /**
     * get account current status
     *
     * @param Account $account
     * @return mixed
     */
    public static function currentStatus(Account $account): mixed
    {
        return AccountStatusLog::with([
            "new_status",
            "old_status",
            "account_status_message",
        ])->where('account_id', $account->id)->latest()->first();
    }


    /**
     * get account all status
     *
     * @param Account $account
     * @return mixed
     */
    public static function getStatus(Account $account): mixed
    {
        return AccountStatusLog::with([
            "new_status",
            "old_status",
            "account_status_message",
        ])->where('account_id', $account->id)->get();
    }
}
