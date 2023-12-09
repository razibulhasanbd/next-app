<?php

namespace App\Console\Commands;

use DateTime;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use App\Models\Trade;
use App\Models\Account;
use App\Models\NewsCalendar;
use App\Models\Subscription;
use App\Services\NewsService;
use Illuminate\Console\Command;
use App\Services\AccountService;
use Illuminate\Support\Facades\Log;

class EvRealNewsTrade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:generate-ev-real-newsTrade';

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
    public function handle(AccountService $accountService, NewsService $newsService)
    {

        $getEvRealAccount = Account::with('plan')->where('breachedby', 'Admin')
            ->get()
            ->groupBy([
                'plan.type'
            ]);

        if (!isset($getEvRealAccount)) return "Not Found";

        try{
            foreach ($getEvRealAccount['Evaluation Real'] as $account) {

                $subscription  = Subscription::find($account->latestSubscription->id);
                $account       = $subscription->account;
                $news          = $newsService->weeksNews(12);
                $tradeArray    = [];
                $newsArray     = [];
                $newsTradeInfo = [];



                $newsTrades    = $accountService->specificSubscriptionNewsCheck($news, $account, $subscription);

                if (count($newsTrades) == 0) {
                    $this->error("kichu nai for $account->login");
                    continue;
                }

                foreach ($newsTrades as $row) {
                    $tradeArray[] = $row['trade_id'];
                    $newsArray [] = $row['news_id'];
                }


                $tradeInfo = Trade::whereIn('id', $tradeArray)->get();
                $newsInfo  = NewsCalendar::whereIn('id', $newsArray)->get();


                foreach ($newsInfo as $singleNews) {
                    $singleNews->date      = Carbon::parse($singleNews->date, 'UTC')->addHours(3)->format('Y-m-d H:i:s');
                    $d                     = new DateTime($singleNews->date, new DateTimeZone(config('app.timezone')));
                    $singleNews->timestamp = strtotime('+3 hours', $d->getTimestamp());
                }

                $tradeInfo = $tradeInfo->keyBy('id');
                $newsInfo  = $newsInfo->keyBy('id');

                $newsInfo  = $newsInfo->toArray();
                $tradeInfo = $tradeInfo->toArray();

                $newsTradeInfoForLog = [];
                foreach ($newsTrades as $row) {
                    $newsTradeInfo[] = array_merge($newsInfo[$row['news_id']], $tradeInfo[$row['trade_id']]);
                }
                foreach($newsTradeInfo as $key => $row){
                    $newsTradeInfoForLog[] = $row['ticket'];
                }
                Log::info("$account->login news", [$newsTradeInfoForLog]);
            }

        }catch (Exception $exception) {
            $this->error($exception->getMessage());
            Log::error("Command exception: ", [$exception]);
        }
    }
}
