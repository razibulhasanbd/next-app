<?php

namespace App\Services;

use App\Constants\AppConstants;
use App\Constants\EmailConstants;
use App\Helper\Helper;
use App\Jobs\EmailJob;
use App\Models\Account;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class PasswordService
{
    /**
     * change account password
     *
     * @param Account $account
     * @param string|null $password
     * @return bool
     */
    public function changeAccountPassword(Account $account, string $password = null): bool
    {
        if ($this->accountPasswordCreateInMetaTraderServer($account, $password = $password ? $password : $this->passwordGenerate())) {
            $account->password = Crypt::encrypt($password);;
            $account->save();
            return true;
        }
        return false;
    }


    /**
     * get account password
     *
     * @param Account $account
     * @return string|null
     */
    public function getAccountPassword(Account $account): string|null
    {
        return $account->password ?? null;
    }


    /**
     * generate investor password
     *
     * @param Account $account
     * @param string|null $password
     * @return boolean
     */
    public function changeInvestorPassword(Account $account, string $password = null): bool
    {
        if ($this->investorPasswordCreateInMetaTraderServer($account, $password = $password ? $password : $this->passwordGenerate())) {
            $account->investor_password = Crypt::encrypt($password);
            $account->save();
            return true;
        }
        return false;
    }


    /**
     * random password generate
     *
     * @param integer $length
     * @return string
     */
    public function passwordGenerate(int $length = 8): string
    {
        $letters = str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $password = substr($letters, 0, $length);
        for ($i = 0; $i < $length / 2; $i++) {
            $rand_index = rand(0, $length - 1);
            $password[$rand_index] = rand(0, 9);
        }
        return  $password;
        // return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $length);
    }


    /**
     * account password create in mt4 server
     *
     * @param Account $account
     * @param string $password
     * @return boolean
     */
    private function accountPasswordCreateInMetaTraderServer(Account $account, string $password): bool
    {
        try {
            $server   = $account->server;
            $response = Http::acceptJson()->retry(3, 200)->post($server->url . "/user/reset_pwd?token=" . $server->login, [
                "login"           => $account->login,
                "password"        => $password,
                "change_investor" => 0,
            ]);

            if ($response->successful()) {
                return true;
            }
            Log::error("Issue in changing password " . $response->body());
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return false;
    }


    /**
     * investor password create in mt4 server
     *
     * @param Account $account
     * @param string $password
     * @return boolean
     */
    private function investorPasswordCreateInMetaTraderServer(Account $account, string $password): bool
    {
        try {
            $server   = $account->server;
            $response = Http::acceptJson()->retry(3, 200)->post($server->url . "/user/reset_pwd?token=" . $server->login, [
                "login"             => $account->login,
                "password_investor" => $password,
                "change_investor"   => 1,
            ]);

            if ($response->successful()) {

                $details = [
                    'template_id' => EmailConstants::INVESTOR_PASSWORD_GENERATED,
                    'to_name'     => Helper::getOnlyCustomerName($account->customer->name),
                    'to_email'    => $account->customer->email,
                    'email_body'  => ['name'=> Helper::getOnlyCustomerName($account->customer->name), 'mt4_login_id' => $account->login]
                ];
                EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
                return true;
            }
            Log::error("Issue in changing password " . $response->body());
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return false;
    }


    /**
     * get investor password
     *
     * @param Account $account
     * @return string|null
     */
    public function getInvestorPassword(Account $account): string|null
    {
        return $account->investor_password ?? null;
    }

}
