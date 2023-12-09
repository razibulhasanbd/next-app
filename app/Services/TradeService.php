<?php

namespace App\Services;

use App\Constants\AppConstants;
use App\Jobs\TradeClose;
use App\Models\Account;
use Exception;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Trade service
 */
class TradeService
{
    /**
     * running trade event for closing
     *
     * @param Account $account
     * @param array $runningTrades
     * @param boolean $breachAccountUpdateStatus
     * @param string $queueName
     * @return array
     */
    public function closeRunningTrades(Account $account, $runningTrades = [], bool $breachAccountUpdateStatus = true, string $queueName = AppConstants::QUEUE_TRADE_CLOSE_JOB): array
    {
        try {
            if (!sizeof($runningTrades)) {
                $runningTrades = $this->getRunningTrades($account);
            }

            if (sizeof($runningTrades)) {
                $batch = [];
                foreach ($runningTrades as $runningTradeItem) {
                    $batch[] = new TradeClose($account, $runningTradeItem);
                }

                Bus::batch(
                    $batch
                )->then(function (Batch $batch) {
                })->catch(function (Batch $batch, Throwable $e) {
                    Log::error($e);
                })->finally(function (Batch $batch) use ($account, $breachAccountUpdateStatus) {
                    if ($breachAccountUpdateStatus) {
                        $accountService = new AccountService();
                        $accountService->updateBreachedAccount($account);
                    }
                })->onQueue($queueName)->dispatch();

                return ResponseService::basicResponse(200, "Trade close job event run successfully");
            }
            return ResponseService::basicResponse(200, "No trade found");
        } catch (Exception $exception) {
            Log::error($exception);
            return ResponseService::basicResponse(500, "Internal server error");
        }
    }


    /**
     * account server config
     *
     * @param Account $account
     * @return array
     */
    public function getServerConfig(Account $account): array
    {
        $server = $account->server;
        return [
            "url"          => $server->url,
            "sessionToken" => $server->login
        ];
    }


    /**
     * get running trades
     *
     * @param Account $account
     * @return array
     */
    public function getRunningTrades(Account $account): array
    {
        $serverConfig = $this->getServerConfig($account);
        $response = Http::retry(3, 300)->acceptJson()->get($serverConfig['url'] . "/trades/online/$account->login", [
            'token' => $serverConfig['sessionToken'],
        ]);
        $response = json_decode($response->body());
        $response = array_filter($response);
        if (!sizeof($response)) {
            $response = Http::retry(3, 300)->acceptJson()->get($serverConfig['url'] . "/trades/online/$account->login", [
                'token' => $serverConfig['sessionToken'],
            ]);
            $response = json_decode($response->body());
            $response = array_filter($response);
        }
        return $response;
    }


    /**
     * get old(history) trades
     *
     * @param Account $account
     * @param string|null $fromDate
     * @param string|null $toDate
     * @return array
     */
    public function getOldTrades(Account $account, string $fromDate = null, string $toDate = null): array
    {
        $serverConfig = $this->getServerConfig($account);

        if ($fromDate != null && $toDate != null) {
            $payload = [
                'token' => $serverConfig['sessionToken'],
                'from'  => $fromDate,
                'to'    => $toDate
            ];
        } else {
            $payload = [
                'token' => $serverConfig['sessionToken'],
            ];
        }

        $response = Http::retry(3, 300)->acceptJson()->get($serverConfig['url'] . "/trades/history/$account->login", $payload);
        $response = json_decode($response->body());
        $response = array_filter($response);
        if (!sizeof($response)) {
            $response = Http::retry(3, 300)->acceptJson()->get($serverConfig['url'] . "/trades/history/$account->login", $payload);
            $response = json_decode($response->body());
            $response = array_filter($response);
        }
        return $response;
    }


    /**
     * get all trades for an account
     *
     * @param Account $account
     * @param string|null $fromDate
     * @param string|null $toDate
     * @return array
     */
    public function getAllTrades(Account $account, string $fromDate = null, string $toDate = null): array
    {
        $runningTrades = $this->getRunningTrades($account);
        $oldTrades     = $this->getOldTrades($account, $fromDate, $toDate);
        $allTrades     = array_merge($runningTrades, $oldTrades);

        return $allTrades;
    }

    /**
     * close single trade
     *
     * @param Account $account
     * @param object $tradeValues
     * @return array
     */
    public function closeTrade(Account $account, object $tradeValues): array
    {
        try {
            $serverConfig = $this->getServerConfig($account);
            if ($tradeValues->type >= 2 && $tradeValues->type <= 5) {
                $response = Http::retry(3, 300)->acceptJson()->post($serverConfig['url'] . "/trades/cancel?token=" . $serverConfig['sessionToken'], [
                    'ticket' => $tradeValues->ticket,
                ]);
            } else {
                $response = Http::retry(3, 300)->acceptJson()->post($serverConfig['url'] . "/trades/close?token=" . $serverConfig['sessionToken'], [
                    'ticket' => $tradeValues->ticket,
                    'lots'   => $tradeValues->volume,
                    'price'  => $tradeValues->close_price,
                ]);
            }
            if ($response->successful()) {
                return ResponseService::basicResponse(200, "Trade closed successfully");
            } else {
                return ResponseService::basicResponse(401, "Api connection is not successfully");
            }
        } catch (Exception $exception) {
            Log::error("Trade close failed-----: ", [$exception]);
            Log::warning("Trade information ------", [$account->login, $tradeValues]);
            return ResponseService::basicResponse(500, "Internal server error");
        }
    }


    /**
     * close all bulk trade
     *
     * @param Account $account
     * @return array
     */
    public function bulkTradeClose(Account $account) : array
    {
        try {
            $serverConfig = $this->getServerConfig($account);
            $response = Http::retry(3, 300)->acceptJson()->get($serverConfig['url'] . "/trades/close/" . $account->login . "?token=" . $serverConfig['sessionToken']);
            if ($response->successful()) {
                $response=json_decode($response->body());
                if ($response->message == 'All Trade Successfully Closed') {
                    Log::info("All trade close success for account ". $account->login);
                    return ResponseService::basicResponse(200, "Trade closed successfully");
                } else if ($response->message == 'Failed To Close All Trade') {
                    Log::notice("All trade close failed for account " . $account->login, [$response]);
                    return ResponseService::basicResponse(401, "Failed To Close All Trade");
                }else if($response->message == 'No Running Trade'){
                    Log::info("No trade found for account " . $account->login);
                    return ResponseService::basicResponse(200, "No Running Trade");
                }
            } else {
                return ResponseService::basicResponse(401, "Api connection is not successfully");
            }
        } catch (Exception $exception) {
            Log::error("Bulk trade close failed account login-----: ". $account->login, [$exception]);
            return ResponseService::basicResponse(500, "Internal server error");
        }
    }


}
