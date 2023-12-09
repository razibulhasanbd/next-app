<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TradeCloseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $trades;
    protected $sessionToken;
    protected $serverUrl;

    public function __construct($tradeCloseInfo)
    {
        $this->trades = $tradeCloseInfo['trades'];
        $this->sessionToken = $tradeCloseInfo['sessionToken'];
        $this->serverUrl = $tradeCloseInfo['server_url'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        $allTrades = $this->trades;

        foreach ($allTrades as $trade) {

            if ($trade['type'] >= 2 && $trade['type'] <= 5) {
                $tradeClose = Http::acceptJson()->post($this->serverUrl . "/trades/cancel?token=" . $this->sessionToken, [
                    'ticket' => $trade['ticket'],
                ]);
            } else {

                $tradeClose = Http::acceptJson()->post($this->serverUrl . "/trades/close?token=" . $this->sessionToken, [
                    'ticket' => $trade['ticket'],
                    'lots' => $trade['volume'],
                    'price' => $trade['close_price'],
                ]);
            }
        }
    }
}
