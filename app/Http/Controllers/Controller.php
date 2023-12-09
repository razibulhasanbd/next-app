<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Trade;
use Illuminate\Http\Request;
use App\Services\NewsService;
use App\Helper\CustomAuthHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // public function testCaa(Request $request){
    //     dd(CustomAuthHelper::getCustomer(), CustomAuthHelper::getAccount());
    //     dd("Authenticate", $request->all());
    // }

    // public function testCca(Request $request){
    //     dd(CustomAuthHelper::getCustomer());

    //     dd("Authenticate", $request->all());
    // }


    public function newsTrade()
    {

        $trades = Trade::with('account.plan')
            ->whereDate('created_at', Carbon::today())
            ->get()
            ->groupBy([
                'account.plan.type'
            ]);

        $yesterdayTrades = Trade::with('account.plan')
            ->whereDate('created_at', Carbon::yesterday())
            ->get()
            ->groupBy(['account.plan.type']);

        $breachAccount = false;
        $newsTradedAccounts = [];

        $news = (new NewsService)->todayNews();
        $yesterdayNews = (new NewsService)->yesterdayNews();

        if (isset($trades['Express Real']) && ($trades['Express Real']->count() > 0)) {
            $newsTimeThreshold = 300;
            $accountList = NewsService::checkNews($trades['Express Real'], $news, $newsTimeThreshold, $breachAccount);
            if (count($accountList) > 0) {
                array_push($newsTradedAccounts, ...$accountList);
            }
        }
        if (isset($trades['Evaluation Real']) && ($trades['Evaluation Real']->count() > 0)) {
            $newsTimeThreshold = 120;
            $accountList =  NewsService::checkNews($trades['Evaluation Real'], $news, $newsTimeThreshold, $breachAccount);
            if (count($accountList) > 0) {
                array_push($newsTradedAccounts, ...$accountList);
            }
        }
        $yesterdayNewsTradedAccounts = [];

        if (isset($yesterdayTrades['Express Real']) && ($yesterdayTrades['Express Real']->count() > 0)) {
            $newsTimeThreshold = 300;
            $accountList = NewsService::checkNews($yesterdayTrades['Express Real'], $yesterdayNews, $newsTimeThreshold, $breachAccount);
            if (count($accountList) > 0) {
                array_push($yesterdayNewsTradedAccounts, ...$accountList);
            }
        } 
        if (isset($yesterdayTrades['Evaluation Real']) && (count($yesterdayTrades['Evaluation Real']) > 0)) {
            $newsTimeThreshold = 120;
            // return $yesterdayTrades['Evaluation Real'];
           
            $accountList =  NewsService::checkNews($yesterdayTrades['Evaluation Real'], $yesterdayNews, $newsTimeThreshold, $breachAccount);
            if (count($accountList) > 0) {
                array_push($yesterdayNewsTradedAccounts, ...$accountList);
            }
        }


        return [
            'yesterday' => $yesterdayNewsTradedAccounts,
            'today' => $newsTradedAccounts
        ];

        return $newsTradedAccounts;
    }
}
