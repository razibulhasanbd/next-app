<?php

use App\Http\Controllers\Api\V1\Admin\CheckoutController;
use App\Http\Controllers\Api\V1\Admin\CouponController;
use App\Models\Role;
use App\Helper\Helper;
use App\Http\Controllers\AccountController;
use App\Models\Account;
use App\Models\AccountMetric;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RetakeRequestController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\ExtendCycleLogController;
use App\Http\Controllers\Admin\TargetReachedAccountsController;
use App\Http\Controllers\Admin\AccountController as AdminAccountController;
use App\Http\Controllers\Api\V1\Admin\UtilityApiController;
use App\Services\ResponseService;

use App\Http\Controllers\InvestorPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//Route::get('accountview-consistency-report', 'TradeController@ConsistencyReportByDate')->name('trades.ConsistencyReport');

Route::middleware(['api_auth'])->group(function () {


    Route::post('/customer', [CustomerController::class, 'create']);
    Route::get('/customer/{id}', [CustomerController::class, 'show']);
    Route::get('/customer', [CustomerController::class, 'allCustomers']);

    Route::get('/account/{id}', [AccountController::class, 'show']);
    Route::get('/account/all/active', [AccountController::class, 'allActive']);
    Route::get('/accountmetric/{id}', [AccountController::class, 'endpoint']);
    Route::get('/accountmetric/{id}/{date}', [AccountController::class, 'endpointbydate']);
    // Route::post('/account/topup', [AccountController::class, 'topup']);

    Route::get('/account/{id}/trades', [TradeController::class, 'showTradesOfAccount']);
    Route::get('/plan/{id}', [PlanController::class, 'show']);
    Route::get('/plan', [PlanController::class, 'allPlans']);
    Route::post('/plan', [PlanController::class, 'create']);

    Route::get('/consistency-rule/{id}', [TradeController::class, 'consistencyRule']);
    //trade history api
    Route::get('trading-history/{id}', [AdminAccountController::class, 'getAccountTrades']);
    Route::get('all-trades/{id}', [TradeController::class, 'allTrades']);

    //Account Growth Status api
    Route::get('account-growth/{id}', [AdminAccountController::class, 'getAccountGrowthStatus']);

    //Account Breach History
    Route::get('breach-events/{id}', [AccountController::class, 'breachEvents']);
    Route::get('/get-all-announcement', [AnnouncementController::class, 'getAllAnnouncement']);


    Route::post('topup-account', [AccountController::class, 'topupAccount']);

    // All servers
    Route::get('allservers', [AccountController::class, 'allServers']);

    // account server
    Route::get('account-server', [AccountController::class, 'accountServer']);

    //Investor password for frontend
    Route::get('/investor-password-get', [InvestorPasswordController::class, 'getInvestorPassword']);
    Route::post('/investor-password-set', [InvestorPasswordController::class, 'setInvestorPassword']);
    Route::post('/investor-password-email', [InvestorPasswordController::class, 'sendInvestorPasswordResetEmail']);

    //Account password for frontend
    Route::get('/account-password-get', [AdminAccountController::class, 'getAccountPassword'])->name('account-password-get');
    Route::post('/account-password-set', [AdminAccountController::class, 'setAccountPassword'])->name('account-password-set');

});




Route::middleware(['auth'])->group(
    function () {
        //Investor password for admin panel
        Route::get('/investor-password-getfe', [InvestorPasswordController::class, 'getInvestorPassword'])->name('investor-password-get');
        Route::post('/investor-password-setef', [InvestorPasswordController::class, 'setInvestorPassword'])->name('investor-password-set');
        Route::post('/investor-password-emailef', [InvestorPasswordController::class, 'sendInvestorPasswordResetEmail'])->name('investor-password-email');

        //Account password for frontend
        Route::get('/account-password-get', [AdminAccountController::class, 'getAccountPassword'])->name('account-password-get');
        Route::post('/account-password-set', [AdminAccountController::class, 'setAccountPassword'])->name('account-password-set');

        //Account forcely migrate
        Route::post('/account-forcely-migrate', [AdminAccountController::class, 'forceMigrationToNextPhase'])->name('account-forcely-migrate');
});


//Account Plan Rule
Route::get('/account-plan-rules/{id}', [AccountController::class, 'accountPlanRules']);

Route::get('/plan-migrate/{id}', [AccountController::class, 'planMigrate'])->name('accountPlanMigrate');




Route::get('getPlanId/{id}', [TargetReachedAccountsController::class, 'getModalInfo'])->name('getPlanId');

Route::get('/account-retake/{id}', [RetakeRequestController::class, 'eligible'])->name('eligible');
Route::post('/account-retake-request', [RetakeRequestController::class, 'receiveRetakeRequest'])->name('receiveRetakeRequest');



Route::get('/get-all-retake-request', [RetakeRequestController::class, 'retakeRequestList'])->name('retakeRequestList');
Route::get('/retakeRequest/{id}', [RetakeRequestController::class, 'retakeRequestModal'])->name('retakeRequestModal');
Route::post('/approve-retake-request', [RetakeRequestController::class, 'approveRetakeRequest'])->name('approveRetakeRequest');
Route::get('/retakeDenyRequest/{id}', [RetakeRequestController::class, 'retakeDenyRequestModal'])->name('retakeDenyRequestModal');
Route::post('/deny-retake-request', [RetakeRequestController::class, 'denyRetakeRequest'])->name('denyRetakeRequest');

Route::get('check/cycle-extension/{id}', [ExtendCycleLogController::class, 'checkCycleExtension'])->name('check.cycle-extension');

Route::post('update/cycle/extend', [ExtendCycleLogController::class, 'extendCycle'])->name('update.extend-cycle-api.view');


