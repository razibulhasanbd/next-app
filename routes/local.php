<?php

use App\Http\Controllers\TradeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RuleBreachJobController;
use App\Http\Controllers\ScheduledJobsController;
use Illuminate\Support\Facades\Route;
Route::group(['prefix' => 'api', 'as' => 'api.', 'namespace' => 'Api', 'middleware' => ['auth']], function () {

    Route::get('/test', [CustomerController::class, 'test']);
    Route::get('/test/sync-dispatch', [ScheduledJobsController::class, 'tradeSyncDispatcher']);
    Route::get('/test/account/tradeSync/{id}', [TradeController::class, 'tradeSyncAccount']);
    Route::get('/test/profitChecker', [ScheduledJobsController::class, 'profitChecker']);

    Route::get('/test/account/{id}/profitChecker', [ScheduledJobsController::class, 'accountProfitChecker']);

    Route::get('/test/account/{id}/smemDelete', [AccountController::class, 'delSmembers']);
    Route::get('/test/account/{id}/redisrunningtrade', [AccountController::class, 'runningTrade']);
    Route::get('/test/tradeCloseV2', [ScheduledJobsController::class, 'weeklyTradeCloseV3']);

    Route::get('/test/account/{id}/marginclear', [AccountController::class, 'marginClear']);

    Route::get('/test/account/{id}/smemDelete', [AccountController::class, 'delSmembers']);

    Route::get('/test/accounts/smemAllDelete', [AccountController::class, 'delAllSmembers']);

    Route::get('/test/activeTradesCount', [AccountController::class, 'totalRunningTradeCount']);

    Route::get('/test/debug', [AccountController::class, 'debug']);

    Route::get('/test/job/rulebreachDispatcher', [RuleBreachJobController::class, 'ruleBreachDispatcher']);

    Route::get('/test/month-debug', [AccountController::class, 'monthEndDebug']);

    Route::get('/test/ruleBreach', [RuleBreachJobController::class, 'rulesCheckerV2']);
    Route::get('/test/ruleBreachRedo', [RuleBreachJobController::class, 'rulesCheckerV3']);
    Route::get('/test/rules', [RuleBreachJobController::class, 'test']);

    //Account Plan Rule
    Route::get('/account-plan-rules/{id}', [AccountController::class, 'accountPlanRules']);



    Route::get('/test/tradeSync', [ScheduledJobsController::class, 'tradeSyncV2']);
    Route::get('/tradeSyncDispatcher', [ScheduledJobsController::class, 'tradeSyncDispatcher']);
    Route::get('/test/breachTradeSync', [ScheduledJobsController::class, 'breachAccountTradeSync']);
    Route::get('/test/deleteAccounts', [ScheduledJobsController::class, 'deleteAccounts']);
    Route::get('/accountreport/{id}', [AccountController::class, 'accountReport']);

    Route::get('/test/mt-init', [ScheduledJobsController::class, 'mtinit']);

    // Evaluation real account subscription manual update
    Route::get('subscriptionResolve', [AccountController::class, 'accountSubscriptionResolve']);

});


