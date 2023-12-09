<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use Carbon\Carbon;
use App\Models\Trade;
use App\Models\Account;
use Illuminate\Support\Arr;
use App\Models\NewsCalendar;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Services\NewsService;
use App\Services\AccountService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;

class NewsController extends Controller
{
    public function getThisWeekNews()
    {
        $weekStartDate = Carbon::now('-05:00')->startOfWeek(Carbon::SUNDAY)->format('Y-m-d H:i:s');
        $weekEndDate = Carbon::now('-05:00')->endOfWeek(Carbon::SATURDAY)->format('Y-m-d H:i:s');
        $getLastWeekData = NewsCalendar::whereBetween('date', [$weekStartDate, $weekEndDate])->delete();
        $getData = Http::get('https://nfs.faireconomy.media/ff_calendar_thisweek.json');
        foreach (json_decode($getData) as $data) {

            NewsCalendar::create([
                "title" => $data->title,
                "country" => $data->country,
                "date" =>  Carbon::parse($data->date, '-05:00')->setTimezone('UTC')->format('Y-m-d H:i:s'),
                "impact" => $data->impact,
                "is_restricted" => $data->impact == 'High' ? 1 : 0,
                "forecast" => $data->forecast,
                "previous" => $data->previous

            ]);
        }
        return "Ok";
    }

    public function getThisWeekApiNews(Request $request)
    {

        $timezone = $request->input('timezone');

        $weekStartDate = Carbon::now()->startOfWeek(Carbon::SUNDAY)->format('Y-m-d H:i:s');
        $weekEndDate = Carbon::now()->endOfWeek(Carbon::SATURDAY)->format('Y-m-d H:i:s');
        $getLastWeekData = NewsCalendar::whereBetween('date', [$weekStartDate, $weekEndDate])->get();
        if (count($getLastWeekData) == 0) {
            Artisan::call('news:calendar');
            $getLastWeekData = NewsCalendar::whereBetween('date', [$weekStartDate, $weekEndDate])->get();
        }
        $collection = $getLastWeekData->map(function ($item) use ($timezone) {
            $item->day = Carbon::parse($item->date, 'UTC')->setTimezone($timezone)->format('l');
            $item->timestamp = Carbon::parse($item->date, 'UTC')->setTimezone($timezone)->timestamp;
            $item->date = Carbon::parse($item->date, 'UTC')->setTimezone($timezone)->isoFormat('Do MMM, hh:mm A');
            $item->restriction = $item->is_restricted == 1 ? 'Restrictions' : 'No Restrictions';
            return $item;
        });
        $getUniqueCountry = NewsCalendar::groupBy('country')->pluck('country');
        return response()->json([
            'getLastWeekData' => $collection,
            'getUniqueCountry' => $getUniqueCountry,
        ]);
    }


    public function specificNewsTradeCheckView($id, AccountService $accountService, NewsService $newsService)
    {

        $subscription = Subscription::find($id);
        $account = $subscription->account;
        $news = $newsService->weeksNews(12);

        $tradeArray = [];
        $newsArray = [];
        $newsTradeInfo = [];
        $newsTrades = $accountService->specificSubscriptionNewsCheck($news, $account, $subscription);

        if (count($newsTrades) == 0) {
            return view('admin.news.specific-sub-news', compact('newsTradeInfo'));
        }

        foreach ($newsTrades as $row) {
            $tradeArray[] = $row['trade_id'];
            $newsArray[] = $row['news_id'];
        }


        $tradeInfo = Trade::whereIn('id', $tradeArray)->get();
        $newsInfo = NewsCalendar::whereIn('id', $newsArray)->get();


        foreach ($newsInfo as $singleNews) {
            $originalDate = $singleNews->date;

            $singleNews->date = Carbon::parse($singleNews->date, 'UTC')->addHours(3)->format('Y-m-d H:i:s');
            $d = new DateTime($singleNews->date, new DateTimeZone(config('app.timezone')));
            $singleNews->timestamp = strtotime('+3 hours', $d->getTimestamp());
        }


        $tradeInfo = $tradeInfo->keyBy('id');
        $newsInfo = $newsInfo->keyBy('id');



        $newsInfo = $newsInfo->toArray();
        $tradeInfo = $tradeInfo->toArray();
        foreach ($newsTrades as $row) {
            $newsTradeInfo[] = array_merge($newsInfo[$row['news_id']], $tradeInfo[$row['trade_id']]);
        }
        $newsTradeInfo = collect($newsTradeInfo);
        return view('admin.news.specific-sub-news', compact('newsTradeInfo'));
    }


    public function newsTradeCheckView($id, AccountService $accountService, NewsService $newsService)
    {

        $account = Account::find($id);
        $news = $newsService->weeksNews(5);
        $tradeArray = [];
        $newsArray = [];
        $newsTradeInfo = [];

        $newsTrades = $accountService->checkNewsTrades($news, $account);
        if (count($newsTrades) == 0) {
            return view('admin.news.news-trade', compact('newsTradeInfo'));
        }

        foreach ($newsTrades as $row) {
            $tradeArray[] = $row['trade_id'];
            $newsArray[] = $row['news_id'];
        }


        $tradeInfo = Trade::whereIn('id', $tradeArray)->get();
        $newsInfo = NewsCalendar::whereIn('id', $newsArray)->get();


        foreach ($newsInfo as $singleNews) {
            $originalDate = $singleNews->date;

            $singleNews->date = Carbon::parse($singleNews->date, 'UTC')->addHours(3)->format('Y-m-d H:i:s');
            $d = new DateTime($singleNews->date, new DateTimeZone(config('app.timezone')));
            $singleNews->timestamp = strtotime('+3 hours', $d->getTimestamp());
        }


        $tradeInfo = $tradeInfo->keyBy('id');
        $newsInfo = $newsInfo->keyBy('id');



        $newsInfo = $newsInfo->toArray();
        $tradeInfo = $tradeInfo->toArray();
        foreach ($newsTrades as $row) {
            $newsTradeInfo[] = array_merge($newsInfo[$row['news_id']], $tradeInfo[$row['trade_id']]);
        }
        $newsTradeInfo = collect($newsTradeInfo);
        return view('admin.news.news-trade', compact('newsTradeInfo'));
    }
}
