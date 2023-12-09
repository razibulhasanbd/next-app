<?php

namespace App\Services;

use DateTime;
use DateTimeZone;
use Carbon\Carbon;

use App\Models\Account;

use App\Models\GrowthFund;

use App\Jobs\BreachEventJob;
use App\Models\NewsCalendar;
use App\Models\AccountMetric;
use App\Constants\AppConstants;
use Illuminate\Support\Facades\Log;
use App\Services\AccountService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;


class NewsService
{
    /**
     * Get running week's restricted news after converting to UTC +3
     *
     * @return Illuminate\Database\Eloquent\Collection
     */

    public function weeksNews($weeks)
    {
        //fetch news from news table for last $weeks weeks
        $news = NewsCalendar::where('is_restricted', 1)->whereBetween('date', [Carbon::now()->subWeeks($weeks)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->get();

        foreach ($news as $singleNews) {
            $originalDate = $singleNews->date;

            $singleNews->date = Carbon::parse($singleNews->date, 'UTC')->addHours(3)->format('Y-m-d H:i:s');

            $d = new DateTime($singleNews->date, new DateTimeZone(config('app.timezone')));
            $singleNews->timestamp = strtotime('+3 hours', $d->getTimestamp());
        }


        return $news;
    }


    /**
     * Get today's restricted news after converting to UTC +3
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function todayNews()
    {
        $news = NewsCalendar::where('is_restricted', 1)->whereBetween('date', [Carbon::yesterday(), Carbon::tomorrow()])->get();



        foreach ($news as $singleNews) {
            $originalDate = $singleNews->date;

            $singleNews->date = Carbon::parse($singleNews->date, 'UTC')->addHours(3)->format('Y-m-d H:i:s');
            $d = new DateTime($singleNews->date, new DateTimeZone(config('app.timezone')));
            $singleNews->timestamp = strtotime('+3 hours', $d->getTimestamp());
        }

        //filter news where date is today
        $news = $news->filter(function ($news) {
            return Carbon::parse($news->date)->isToday();
        });


        return $news->flatten();
    }

    /**
     * Get yesterday's high impact news after converting to UTC +3
     *
     *
     */
    public function yesterdayNews()
    {
        $news = NewsCalendar::where('is_restricted', 1)->whereBetween('date', [Carbon::yesterday(), Carbon::today()])->get();
        foreach ($news as $singleNews) {
            $originalDate = $singleNews->date;

            $singleNews->date = Carbon::parse($singleNews->date, 'UTC')->addHours(3)->format('Y-m-d H:i:s');
            $d = new DateTime($singleNews->date, new DateTimeZone(config('app.timezone')));
            $singleNews->timestamp = strtotime('+3 hours', $d->getTimestamp());
        }


        return $news;
    }

    public static function checkNews($trades, $news, $newsTimeThreshold, $breachAccount = false)
    {
        $newsTrades = [];
        $mappedNewsTrades = [];
        $newsTradedAccounts = [];
        $accountService = new AccountService();


        //check for each $news['country'] string is in $trades['symbol'] string
        foreach ($news as $singleNews) {
            foreach ($trades as $trade) {
                $isNewsTrade = self::checkIfNewsTrade($trade, $singleNews, $newsTimeThreshold);
                if ($isNewsTrade) {
                    $account = Account::find($trade->account_id);

                    if ((!$account->breached) && (!in_array($account->id, $newsTradedAccounts))) {
                        $newsTradedAccounts[] = $account->id;
                        if ($breachAccount) {
                            $margin = $accountService->margin($account);
                            // BreachEventJob::dispatch($account, "news-trade", $margin)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
                        }
                        continue;
                    } else {
                        continue;
                    }
                }
            }
        }

        return  $newsTradedAccounts;
    }


    public static function checkIfNewsTrade($trade, $singleNews, $newsTimeThreshold): bool
    {
        $pairMap = [
            'USD' => ['XAUUSD', 'NDX100', 'SPX500', 'US2000', 'US30', 'USDX', 'USOUSD', 'XAGUSD', 'NDX100.i', 'SPX500.i', 'US2000.i', 'US30.i'],
            'GBP' => ['UK100', 'UKOUSD', 'UK100.i'],
            'AUD' => ['AUS200', 'XAUUSD', 'AUS200.i'],
            'JPY' => ['JPN225', 'JPN225.i', 'JAPAN225'],
            'EUR' => ['FRA40', 'EUSTX50', 'GER30', 'GER30.i', 'FRA40.i', 'EUSTX50.i'],
        ];

        if (str_contains($trade['symbol'], $singleNews['country'])) {
            //check if $trade['open_time'] is between 120 seconds of $singleNews['timestamp']
            if (($trade['open_time'] <= ($singleNews['timestamp'] + $newsTimeThreshold)) && ($trade['open_time'] >= ($singleNews['timestamp'] - $newsTimeThreshold))) {

                return true;
            } else if (($trade['close_time'] <= $singleNews['timestamp'] + $newsTimeThreshold) && ($trade['close_time'] >= $singleNews['timestamp'] - $newsTimeThreshold)) {

                return true;
            }
        } else {

            foreach ($pairMap as $currency => $symbolArray) {

                if ($singleNews['country'] == $currency) {
                    if (in_array($trade['symbol'], $symbolArray)) {

                        if (($trade['open_time'] <= ($singleNews['timestamp'] + $newsTimeThreshold)) && ($trade['open_time'] >= ($singleNews['timestamp'] - $newsTimeThreshold))) {

                            return true;
                        } else if (($trade['close_time'] <= $singleNews['timestamp'] + $newsTimeThreshold) && ($trade['close_time'] >= $singleNews['timestamp'] - $newsTimeThreshold)) {

                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }
}
