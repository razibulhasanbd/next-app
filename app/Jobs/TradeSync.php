<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Trade;
use App\Models\Account;
use App\Models\AccountMetric;
use App\Services\TradeService;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Log;

class TradeSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $account;
    private $fromHours;
    private $toHours;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Account $account, int $fromHours = 34, int $toHours = 24)
    {
        $this->account   = $account;
        $this->fromHours = $fromHours;
        $this->toHours   = $toHours;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $telescope     = [];
        $account       = $this->account;
        $tradeService  = new TradeService();
        $from          = Carbon::now()->subHours($this->fromHours)->format('Y.m.d H:i');
        $to            = Carbon::now()->addHours($this->toHours)->format('Y.m.d H:i');
        $accountTrades = $tradeService->getAllTrades($account, $from, $to);

        foreach ($accountTrades as $report) {
            $report = (array) $report;

            if (!is_array($report)) continue;


            if ($report['type'] > 1) {
                continue;
            }

            $report['account_id'] = $account->id;
            if ($report['swap'] < 0) {
                $report['swap'] = 0;
            } else if ($report['swap'] > 2047483647) {
                $report['swap'] = 0;
            }
            $report['swap'] = round($report['swap'], 4);

            $update = Trade::updateOrCreate([
                'ticket'     => $report['ticket'],
                'account_id' => $account->id

            ], $report);

            // return $update->wasRecentlyCreated;
            if ($update->wasRecentlyCreated) {


                $reportDate = Carbon::createFromTimestamp($report["open_time"])->toDateString();
                // $reportDate = date('Y-m-d', $report["open_time"]);


                $createdMetric = AccountMetric::where('account_id', $account->id)->whereDate('metricDate', $reportDate)->first();
                if ($createdMetric == null) {

                    return [$account->id, $reportDate];
                }

                $createdMetric->isActiveTradingDay = true;
                $createdMetric->trades = $createdMetric->trades + 1;
                $createdMetric->save();

                // return $createdMetric;
                // $createdMetric->save();
                // $telescope[] = "+++++++++  $report[ticket] Trade was missing ++++++++++++";
                // $telescope[] = "Trade count increased for metric date : $reportDate";
            }
        }

        // return $telescope;
    }
}
