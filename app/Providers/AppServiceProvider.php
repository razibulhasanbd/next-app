<?php

namespace App\Providers;

use App\Models\Account;
use ConsoleTVs\Charts\Registrar as Charts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $charts->register([
        //     \App\Charts\DashboardChart::class
        // ]);

        // view()->composer('home', function($view){
        //     $totalAccount = Account::count();
        //     $totalAccountPerPlan = Account::select('plan_id',DB::raw('count(*) as total'))->groupBy('plan_id')->get();
        //     $totalBreach = Account::whereBreached('1')->count();
        //     $breachedAccountPerPlan = Account::whereBreached('1')->select('plan_id',DB::raw('count(*) as total'))->groupBy('plan_id')->get();
        //     $view
        //     ->with('totalAccount',$totalAccount)
        //     ->with('totalAccountPerPlan', $totalAccountPerPlan)
        //     ->with('totalBreach', $totalBreach)
        //     ->with('breachedAccountPerPlan',$breachedAccountPerPlan);
            
           
        // });
    }
}
