<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Trade;
use App\Models\Account;
use App\Models\Package;
use App\Models\BreachEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Services\ProfitChecker\ProfitCheckerService;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $breachPlanName = [];
        $breachAccountCount = [];
        $accountPackage = [];
        $accountCount = [];
        $breachType = [];
        $breachTypeAccountCount = [];
        $userCount=[];
        $planName=[];
        if ($request->startDate) {

            $startDate = Carbon::createFromFormat('m/d/Y', $request->startDate)->format('Y-m-d');

            $endDate = Carbon::createFromFormat('m/d/Y', $request->endDate)->format('Y-m-d');

            $totalAccountPerPlan = Account::with('plan')->select('plan_id', DB::raw('count(*) as total'))->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])->groupBy('plan_id')->get();
            // $breachedAccountPerPlan = Account::with('plan')->whereBreached('1')->select('plan_id', DB::raw('count(*) as total'))->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])->groupBy('plan_id')->get();

            $breachedAccounts = BreachEvent::pluck('account_id');
            $breachedAccountPerPlan = Account::with('plan')->whereIn('id',$breachedAccounts)->select('plan_id', DB::raw('count(*) as total'))->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])->groupBy('plan_id')->get()->toArray();

            $chunkSize = 1000;
            $chunks = array_chunk($breachedAccountPerPlan, $chunkSize);

            foreach ($chunks as $chunk) {
                $breachedAccountPerPlan=$chunk;
            }

            $totalAccountCountByBreachType = Account::with('plan')->whereBreached('1')->select('breachedby', DB::raw('count(*) as total'))->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])->groupBy('breachedby')->get();

            // Accounts Chart
            $allPackage = Package::all();
            foreach ($allPackage as $package) {
                $accountPackage[] = $package->name;
                $accountCount[] = count($package->accounts()->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']));
            }

            // Trades chart
            $allTrades = Trade::select(

                DB::raw('COUNT(*) as total_trade'),
                DB::raw('SUM(CASE
                        WHEN close_time != 0 THEN 1 ELSE 0 END) AS close_trade'),
                DB::raw('SUM(CASE
                            WHEN close_time = 0 THEN 1 ELSE 0 END) AS open_trade')
            )->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])->get();


            foreach ($totalAccountPerPlan as $key => $value) {
                $planName[] = $value->plan->title;
                $userCount[] = $value->total;
            }

            foreach ($breachedAccountPerPlan as $key => $value) {
                $breachPlanName[] = $value['plan']['title'];
                $breachAccountCount[] = $value['total'];
            }

            $tradesLabel = ['Total Trades', 'Open Trades', 'Close Trades'];
            $allTradesCount = [(int) $allTrades[0]->total_trade, (int) $allTrades[0]->open_trade, (int) $allTrades[0]->close_trade];

            foreach ($totalAccountCountByBreachType as $key => $value) {
                $breachType[] = $value->breachedby;
                $breachTypeAccountCount[] = $value->total;
            }
           return response()->json(array('userTotalCount' => $userCount, 'planName' => $planName, 'breachPlanName' => $breachPlanName, 'breachAccountCount' => $breachAccountCount, 'accountPackage' => $accountPackage, 'accountCount' => $accountCount, 'tradesLabel' => $tradesLabel, 'allTradesCount' => $allTradesCount, 'breachType' => $breachType, 'breachTypeAccountCount' => $breachTypeAccountCount));
        }

        return view('home');
    }

}
