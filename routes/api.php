<?php

use App\Constants\AppConstants;

use App\Http\Controllers\Api\V1\Admin\CardController;
use App\Http\Controllers\Api\V1\Admin\CouponController;
use App\Http\Controllers\Api\V1\Admin\CustomerCardController;
use App\Http\Controllers\Api\V1\Admin\KycVeriffWebhookController;
use App\Http\Controllers\Api\V1\Admin\MT5Controller;
use App\Http\Controllers\Api\V1\Admin\OrderController;
use App\Http\Controllers\Api\V1\Admin\StripeController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\TradingOverviewController;
use App\Http\Controllers\ScheduledJobsController;
use App\Jobs\InvoiceGenerateJob;
use App\Models\Orders;
use App\Services\Checkout\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\RedisController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TraderGameController;
use App\Http\Controllers\ServerUptimeController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\TypeformController;
use App\Http\Controllers\Admin\RefundRequestController;
use App\Http\Controllers\InvestorPasswordController;
use App\Http\Controllers\Api\V1\Admin\CheckoutController;
use App\Http\Controllers\Api\V1\Admin\IPWisePaymentMethod;
use App\Http\Controllers\Api\V1\Admin\UtilityApiController;
use App\Http\Controllers\Admin\TargetReachedAccountsController;
use App\Http\Controllers\Api\V1\AccountEligibilityApiController;

use App\Http\Controllers\Admin\AccountController as AdminAccountController;

use App\Http\Controllers\Api\V1\Admin\NotificationController;



Route::post('/receivetrades', [TradeController::class, 'receiveTrade']);
Route::get('/testRedis', [RedisController::class, 'testRedis'])->middleware('custom_client_auth');

Route::post('/refunds/get-update', [RefundRequestController::class, 'webhookRefundCallBack']);

Route::post('/refunds/get-update', [RefundRequestController::class, 'webhookRefundCallBack']);

Route::post('/webhook-outsidePayment', [TypeformController::class, 'webhookOutsidePayment'])->middleware('custom_client_auth');

Route::group(['middleware' => ['custom_client_auth', 'cors']], function() {
    Route::post('/webhook-outsidePayment', [TypeformController::class, 'webhookOutsidePayment']);
// Route::post('/webhook-outsidePayment', [TypeformController::class, 'webhookOutsidePayment']);
});

Route::post('/webhook-outsidePayment-lander', [TypeformController::class, 'webhookOutsidePayment'])->withoutMiddleware('throttle:10000,1')->middleware('throttle:5,1');



Route::get('/create-news', [NewsController::class, 'getThisWeekNews']);

Route::middleware(['api_auth'])->group(function () {

    Route::get('/trader-game/user/{id}', [TraderGameController::class, 'show']);
    Route::post('/trader-game', [TraderGameController::class, 'store']);
    Route::get('/trader-game/user/{id}/{date}', [TraderGameController::class, 'showDate']);
    Route::post('/news-calendar', [NewsController::class, 'getThisWeekApiNews']);
});

Route::get('/test/mt-init', [ScheduledJobsController::class, 'mtinit']);


Route::get('/uptime/redis', [ServerUptimeController::class, 'redisServerCheck']);
Route::get('/uptime/mt4', [ServerUptimeController::class, 'mt4ServerCheck']); //not
Route::get('/uptime/redis-set', [ServerUptimeController::class, 'redisSetCheck']);


// Account Rule
Route::apiResource('account-rules', 'AccountRuleApiController');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// Type
Route::apiResource('types', 'TypeApiController');

// Tag
Route::apiResource('tags', 'TagApiController');

// Section
Route::apiResource('sections', 'SectionApiController');

// Question
Route::post('questions/media', 'QuestionApiController@storeMedia')->name('questions.storeMedia');
Route::apiResource('questions', 'QuestionApiController');
Route::post('/all-question', [QuestionController::class, 'get_selected_question']);


if (App::environment('local')) {
    Route::get('/test/close-trade/{id}', [AccountController::class, 'closeRunningTrades']);
    Route::get('/test/ea-trade-stats', [TradeController::class, 'eaStats']);
    Route::get('/test/risk-management', [TradeController::class, 'riskManagement']);

}

Route::get('/news-trade',[Controller::class, 'newsTrade']);

// account testing
// Route::get('account/self-eligibility-check', [AccountEligibilityApiController::class, 'status']);
// Route::post('account/self-eligibility-action', [AccountEligibilityApiController::class, 'action']);

// for evaluation
Route::get('account/self-eligibility-check', [AccountEligibilityApiController::class, 'status'])->middleware('custom_account_auth');
Route::post('account/self-eligibility-action', [AccountEligibilityApiController::class, 'action'])->middleware('custom_account_auth');


//utility category
Route::get('/get-utility-categories', [UtilityApiController::class, 'getUtilityCategories'])->name('getUtilityCategories');
//utility items
Route:: get('/get-utility-items', [UtilityApiController::class, 'getUtilityItems'])->name('getUtilityItems');
//utility items and category
Route::get('/get-utility-item-category', [UtilityApiController::class, 'getUtilityAndCategory'])->name('getUtilityAndCategory');



Route::middleware(['custom_client_auth'])->group(function () {
    //Investor password for frontend
    Route::get('/investor-password-get', [InvestorPasswordController::class, 'getInvestorPassword']);
    Route::post('/investor-password-set', [InvestorPasswordController::class, 'setInvestorPassword']);
    Route::post('/investor-password-email', [InvestorPasswordController::class, 'sendInvestorPasswordResetEmail']);
});


// Route::middleware(['custom_account_auth'])->group(function () {
//     Route::post('/caa', [Controller::class, 'testCaa']);
// });

// Google sheet record endpoint
Route::get('google-sheet-records-marketing-team-gcsvcaghjgsjac', [TypeformController::class, 'googleSheetRecords'])->name('googleSheetRecords');

// KYC
Route::post('kyc/veriff-webhook', [KycVeriffWebhookController::class,'veriffWebhook'])->name('veriffWebhook');
Route::get('kyc/verification-status', [KycVeriffWebhookController::class,'verificationStatus']);
Route::get('kyc/pdf', [KycVeriffWebhookController::class,'kycPDFGeneration']); // for test purpose
Route::post('kyc/agreement-submit', [KycVeriffWebhookController::class,'kycAgreementSubmit'])->middleware('custom_client_auth');

//Product and order system
Route::post('product-order', [CheckoutController::class, 'productOrder']);
Route::get('coupon-check', [CouponController::class, 'couponCheck']);
Route::post('customer-cards-info', [CustomerCardController::class, 'cardInfo']);
Route::post('confirm-order', [CheckoutController::class, 'confirmOrder']);

// order for stripe
Route::get('create-payment-intent', [StripeController::class, 'createPaymentIntent'])->middleware('custom_client_auth');



// new dashboard
Route::get('account-overview', [DashboardController::class, 'getAccountInfo'])->middleware('custom_client_auth');
Route::get('trading-overview', [TradingOverviewController::class, 'getTradingInfo'])->middleware('custom_client_auth');
Route::get('trading-history/{id}', [AdminAccountController::class, 'getAccountTrades'])->middleware('custom_client_auth');

// Ofac and non ofac country - payment list
Route::post('ip-wise-payment-method',[IPWisePaymentMethod::class,'paymentMethod']);

// add card
Route::post('add-card-request', [CardController::class, 'addCard'])->middleware('custom_client_auth');
Route::post('confirm-card-request', [CardController::class, 'confirmCard'])->middleware('custom_client_auth');
Route::get('card-list', [CardController::class, 'cardList'])->middleware('custom_client_auth');
Route::get('make-primary-card', [CardController::class, 'makePrimary'])->middleware('custom_client_auth');
Route::get('card-delete', [CardController::class, 'cardDelete'])->middleware('custom_client_auth');


Route::post('existing-cards-payment', [CardController::class, 'cardPayment'])->middleware('custom_account_auth');
Route::get('payment-history', [OrderController::class, 'paymentHistory'])->middleware('custom_account_auth');
Route::get('mt5-account-info', [MT5Controller::class, 'accountInfo']);
Route::get('pending-payment-history', [OrderController::class, 'pendingPaymentHistory'])->middleware('custom_account_auth');
Route::get('payment-history', [OrderController::class, 'paymentHistory'])->middleware('custom_client_auth');

// Route::get('invoice-pdf-generate', function (Request $request) {
//     $invoice = new InvoiceService();
//     $invoice->generateInvoiceByOrderId(1886, 3);
    // InvoiceGenerateJob::dispatch(1886)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
//     return "Lol";
// });

Route::get('payment-history', [OrderController::class, 'paymentHistory'])->middleware('custom_account_auth');;

//Route::middleware(['api_auth'])->group(function () {

    Route::get('/notifications', [NotificationController::class, 'getAllNotifications']);
    Route::put('/notifications/{id}/mark-as-read', [NotificationController::class, 'markUserWebNotificationAsRead']);
    Route::put('/notifications/mark-all-as-read', [NotificationController::class, 'markUserAllWebNotificationsAsRead']);

//});

