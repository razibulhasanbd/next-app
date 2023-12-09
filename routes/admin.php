<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\Admin\TradeController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\TypeformController;
use App\Http\Controllers\Admin\AccountLabelsController;
use App\Http\Controllers\Admin\AccountCertificateController;
use App\Http\Controllers\TradeController as localTradeController;
use App\Http\Controllers\Admin\CountryCategoryController;
use App\Http\Controllers\Admin\PaymentMethodApprovalController;

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});
Auth::routes([

    'register' => false, // Register Routes...
]);
//Auth::routes();
Route::get('accountview-consistency-report', [TradeController::class, 'ConsistencyReportByDate'])->name('trades.ConsistencyReport');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Customer
    Route::delete('customers/destroy', 'CustomerController@massDestroy')->name('customers.massDestroy');
    Route::post('customers/parse-csv-import', 'CustomerController@parseCsvImport')->name('customers.parseCsvImport');
    Route::post('customers/process-csv-import', 'CustomerController@processCsvImport')->name('customers.processCsvImport');
    Route::get('customers/kyc', 'CustomerController@kycInfo')->name('customers.kycInfo');
    Route::post('customers/manual-kyc', 'CustomerController@manualKycEntry')->name('customers.manualKyc');
    Route::post('customers/kyc-approval-status', 'CustomerController@KycApprovalStatus')->name('customers.kycApprovalStatus');
    Route::resource('customers', 'CustomerController');

    // Analytics
    Route::resource('analytics', 'AnalyticsController');

    // Account
    Route::delete('accounts/destroy', 'AccountController@massDestroy')->name('accounts.massDestroy');
    Route::post('accounts/parse-csv-import', 'AccountController@parseCsvImport')->name('accounts.parseCsvImport');
    Route::post('accounts/process-csv-import', 'AccountController@processCsvImport')->name('accounts.processCsvImport');

    Route::get('download/expressReal', 'AccountController@downloadExpressReal')->name('accounts.downloadExpressReal');

    Route::get('download/evReal', 'AccountController@downloadEvReal')->name('accounts.downloadEvReal');

    Route::resource('accounts', 'AccountController');

    Route::get('accounts/id-wise-subscription/{id}', 'AccountController@getSubscription')->name('accounts.subscription');

    Route::get('accounts/id-wise-account-metrics/{id}', 'AccountController@getAccountMetrics')->name('accounts.metrics');

    Route::get('accounts/id-wise-account-trades/{id}', 'AccountController@getAccountTrades')->name('accounts.trades');

    Route::get('accounts/id-wise-account-status/{id}', 'AccountController@getAccountIdForOnOf')->name('accounts.onOF');

    Route::post('accounts/id-wise-topup', 'AccountController@checkTopup')->name('accounts.topup');

    Route::get('accounts/consistency-rule/{id}', 'TradeController@consistencyRule')->name('accounts.consistencyRule');

    Route::post('accounts/id-comment-update', 'AccountController@commentUpdate')->name('accounts.comment.update');

    Route::get('accountsby-date-range', 'AccountController@accountsByDateRange')->name('accounts.accountsByDateRange');

    // Account Metrics
    Route::delete('account-metrics/destroy', 'AccountMetricsController@massDestroy')->name('account-metrics.massDestroy');
    Route::post('account-metrics/parse-csv-import', 'AccountMetricsController@parseCsvImport')->name('account-metrics.parseCsvImport');
    Route::post('account-metrics/process-csv-import', 'AccountMetricsController@processCsvImport')->name('account-metrics.processCsvImport');
    Route::resource('account-metrics', 'AccountMetricsController');

    // Plan
    Route::delete('plans/destroy', 'PlanController@massDestroy')->name('plans.massDestroy');
    Route::post('plans/parse-csv-import', 'PlanController@parseCsvImport')->name('plans.parseCsvImport');
    Route::post('plans/process-csv-import', 'PlanController@processCsvImport')->name('plans.processCsvImport');
    Route::resource('plans', 'PlanController');

    // Subscription
    Route::delete('subscriptions/destroy', 'SubscriptionController@massDestroy')->name('subscriptions.massDestroy');
    Route::post('subscriptions/parse-csv-import', 'SubscriptionController@parseCsvImport')->name('subscriptions.parseCsvImport');
    Route::post('subscriptions/process-csv-import', 'SubscriptionController@processCsvImport')->name('subscriptions.processCsvImport');
    Route::resource('subscriptions', 'SubscriptionController');

    // Trade
    Route::delete('trades/destroy', 'TradeController@massDestroy')->name('trades.massDestroy');
    Route::post('trades/parse-csv-import', 'TradeController@parseCsvImport')->name('trades.parseCsvImport');
    Route::post('trades/process-csv-import', 'TradeController@processCsvImport')->name('trades.processCsvImport');
    Route::resource('trades', 'TradeController');
    Route::get('arbitrary-trade-report', 'TradeController@arbitraryTradeReport')->name('trades.arbitraryTradeReport');
    Route::get('ea-trades', 'TradeController@EATrades')->name('trades.EATrades');
    Route::get('tradesby-date-range', 'TradeController@tradesByDateRange')->name('trades.tradesByDateRange');
    Route::get('trades-filter-cal', 'TradeController@tradesFilterCal')->name('trades.tradesFilterCal');



    // tradesyncCheckCreatedat
    Route::get('tradesyncCheck/{id}', [localTradeController::class, 'tradeSyncAccount'])->name('trades.accountTradeSyncCheck');



    // Package
    Route::delete('packages/destroy', 'PackageController@massDestroy')->name('packages.massDestroy');
    Route::post('packages/parse-csv-import', 'PackageController@parseCsvImport')->name('packages.parseCsvImport');
    Route::post('packages/process-csv-import', 'PackageController@processCsvImport')->name('packages.processCsvImport');
    Route::resource('packages', 'PackageController');

    // Mt Server
    Route::delete('mt-servers/destroy', 'MtServerController@massDestroy')->name('mt-servers.massDestroy');
    Route::post('mt-servers/parse-csv-import', 'MtServerController@parseCsvImport')->name('mt-servers.parseCsvImport');
    Route::post('mt-servers/process-csv-import', 'MtServerController@processCsvImport')->name('mt-servers.processCsvImport');
    Route::resource('mt-servers', 'MtServerController');

    // Rule Name
    Route::delete('rule-names/destroy', 'RuleNameController@massDestroy')->name('rule-names.massDestroy');
    Route::post('rule-names/parse-csv-import', 'RuleNameController@parseCsvImport')->name('rule-names.parseCsvImport');
    Route::post('rule-names/process-csv-import', 'RuleNameController@processCsvImport')->name('rule-names.processCsvImport');
    Route::resource('rule-names', 'RuleNameController');

    // Plan Rule
    Route::delete('plan-rules/destroy', 'PlanRuleController@massDestroy')->name('plan-rules.massDestroy');
    Route::post('plan-rules/parse-csv-import', 'PlanRuleController@parseCsvImport')->name('plan-rules.parseCsvImport');
    Route::post('plan-rules/process-csv-import', 'PlanRuleController@processCsvImport')->name('plan-rules.processCsvImport');
    Route::resource('plan-rules', 'PlanRuleController');

    // Retake
    Route::delete('retakes/destroy', 'RetakeController@massDestroy')->name('retakes.massDestroy');
    Route::post('retakes/parse-csv-import', 'RetakeController@parseCsvImport')->name('retakes.parseCsvImport');
    Route::post('retakes/process-csv-import', 'RetakeController@processCsvImport')->name('retakes.processCsvImport');
    Route::resource('retakes', 'RetakeController');

    // Target Reached Accounts
    Route::delete('target-reached-accounts/destroy', 'TargetReachedAccountsController@massDestroy')->name('target-reached-accounts.massDestroy');
    Route::resource('target-reached-accounts', 'TargetReachedAccountsController');

    Route::post('target-reached-accounts/parse-csv-import', 'TargetReachedAccountsController@parseCsvImport')->name('target-reached-accounts.parseCsvImport');
    Route::post('target-reached-accounts/process-csv-import', 'TargetReachedAccountsController@processCsvImport')->name('target-reached-accounts.processCsvImport');

    Route::post('target-reached-accounts-plan-migrate', 'TargetReachedAccountsController@approveAccount')->name('targetReachedAccountsPlanMigrate');

    Route::get('denay-account/{id}', 'TargetReachedAccountsController@denayAccount')->name('denayAccount');

    Route::get('news-trade/{id}', 'TargetReachedAccountsController@newsTradeView')->name('newsTradeView');



    Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');
    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');

    Route::get('open-trade', 'TradeController@openTrade')->name('trades.openTrade');
    Route::get('close-trade', 'TradeController@closeTrade')->name('trades.closeTrade');
    Route::get('arbitrary-trade', 'TradeController@arbitraryTrade')->name('trades.arbitraryTrade');

    Route::get('accountview-consistency-report', [TradeController::class, 'ConsistencyReportByDate'])->name('trades.ConsistencyReport');

    Route::get('show-ticket/{ticket}', 'TradeController@showTradeTicket')->name('trades.showTicket');

    Route::get('breach-event', 'BreachEventController@breachEvent')->name('accounts.breachEvent');
    Route::get('breach-event/{id}', 'BreachEventController@show')->name('accounts.breachEvent.show');
    Route::resource('breach-events', 'BreachEventController');
    //topup view
    Route::get('topup-log', 'AccountController@topUpLog')->name('accounts.topUpLog');

    // Account Profit view
    Route::get('account-profit',  'AccountController@accountProfit')->name('accounts.accountProfit');
    Route::post('account-profit-filter',  'AccountController@accountProfit')->name('accounts.accountProfitFilter');

    Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');
    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');

    // Account Rule Template
    Route::delete('account-rule-templates/destroy', 'AccountRuleTemplateController@massDestroy')->name('account-rule-templates.massDestroy');
    Route::resource('account-rule-templates', 'AccountRuleTemplateController');

    // Growth Fund
    Route::delete('growth-funds/destroy', 'GrowthFundController@massDestroy')->name('growth-funds.massDestroy');
    Route::resource('growth-funds', 'GrowthFundController');

    // Account Rule
    Route::delete('account-rules/destroy', 'AccountRuleController@massDestroy')->name('account-rules.massDestroy');
    Route::resource('account-rules', 'AccountRuleController');

    // Announcement
    Route::delete('announcements/destroy', 'AnnouncementController@massDestroy')->name('announcements.massDestroy');
    Route::post('announcements/media', 'AnnouncementController@storeMedia')->name('announcements.storeMedia');
    Route::post('announcements/ckmedia', 'AnnouncementController@storeCKEditorImages')->name('announcements.storeCKEditorImages');
    Route::resource('announcements', 'AnnouncementController');

    // Approval Category
    Route::delete('approval-categories/destroy', 'ApprovalCategoryController@massDestroy')->name('approval-categories.massDestroy');
    Route::resource('approval-categories', 'ApprovalCategoryController');

    // Category
    Route::delete('categories/destroy', 'CategoryController@massDestroy')->name('categories.massDestroy');
    Route::resource('categories', 'CategoryController');

    // Type
    Route::delete('types/destroy', 'TypeController@massDestroy')->name('types.massDestroy');
    Route::resource('types', 'TypeController');

    // Tag
    Route::delete('tags/destroy', 'TagController@massDestroy')->name('tags.massDestroy');
    Route::resource('tags', 'TagController');

    // Section
    Route::delete('sections/destroy', 'SectionController@massDestroy')->name('sections.massDestroy');
    Route::resource('sections', 'SectionController');

    // Question
    Route::delete('questions/destroy', 'QuestionController@massDestroy')->name('questions.massDestroy');
    Route::post('questions/media', 'QuestionController@storeMedia')->name('questions.storeMedia');
    Route::post('questions/ckmedia', 'QuestionController@storeCKEditorImages')->name('questions.storeCKEditorImages');
    Route::post('questions/parse-csv-import', 'QuestionController@parseCsvImport')->name('questions.parseCsvImport');
    Route::post('questions/process-csv-import', 'QuestionController@processCsvImport')->name('questions.processCsvImport');
    Route::resource('questions', 'QuestionController');

    // Trader Game
    Route::delete('trader-games/destroy', 'TraderGameController@massDestroy')->name('trader-games.massDestroy');
    Route::resource('trader-games', 'TraderGameController');

    // Extend Cycle
    Route::get('extend/cycle', 'ExtendCycleLogController@getAllExtendCycle')->name('extend-cycle.index');
    Route::get('extend/cycle/{id}', 'ExtendCycleLogController@viewExtendCycle')->name('extend-cycle.view');
    Route::get('check/extend/cycle', 'ExtendCycleLogController@checkExtendCycle')->name('check.extend-cycle.view');
    Route::get('update/extend/cycle/{id}/{date}/{week}', 'ExtendCycleLogController@updateExtendCycle')->name('update.extend-cycle.view');


    // Extend Cycle Log
    Route::delete('extend-cycle-logs/destroy', 'ExtendCycleLogController@massDestroy')->name('extend-cycle-logs.massDestroy');
    Route::post('extend-cycle-logs/parse-csv-import', 'ExtendCycleLogController@parseCsvImport')->name('extend-cycle-logs.parseCsvImport');
    Route::post('extend-cycle-logs/process-csv-import', 'ExtendCycleLogController@processCsvImport')->name('extend-cycle-logs.processCsvImport');
    Route::resource('extend-cycle-logs', 'ExtendCycleLogController');

    // Trade Sl Tp
    Route::delete('trade-sl-tps/destroy', 'TradeSlTpController@massDestroy')->name('trade-sl-tps.massDestroy');
    Route::post('trade-sl-tps/parse-csv-import', 'TradeSlTpController@parseCsvImport')->name('trade-sl-tps.parseCsvImport');
    Route::post('trade-sl-tps/process-csv-import', 'TradeSlTpController@processCsvImport')->name('trade-sl-tps.processCsvImport');
    Route::resource('trade-sl-tps', 'TradeSlTpController');

    // Certificate Type
    Route::delete('certificate-types/destroy', 'CertificateTypeController@massDestroy')->name('certificate-types.massDestroy');
    Route::post('certificate-types/parse-csv-import', 'CertificateTypeController@parseCsvImport')->name('certificate-types.parseCsvImport');
    Route::post('certificate-types/process-csv-import', 'CertificateTypeController@processCsvImport')->name('certificate-types.processCsvImport');
    Route::resource('certificate-types', 'CertificateTypeController');



    // Ceritificate
    Route::delete('ceritificates/destroy', 'CeritificateController@massDestroy')->name('ceritificates.massDestroy');
    Route::post('ceritificates/parse-csv-import', 'CeritificateController@parseCsvImport')->name('ceritificates.parseCsvImport');
    Route::post('ceritificates/process-csv-import', 'CeritificateController@processCsvImport')->name('ceritificates.processCsvImport');
    Route::resource('ceritificates', 'CeritificateController');

    // Account Certificate
    Route::delete('account-certificates/destroy', 'AccountCertificateController@massDestroy')->name('account-certificates.massDestroy');
    Route::post('account-certificates/parse-csv-import', 'AccountCertificateController@parseCsvImport')->name('account-certificates.parseCsvImport');
    Route::post('account-certificates/process-csv-import', 'AccountCertificateController@processCsvImport')->name('account-certificates.processCsvImport');
    Route::resource('account-certificates', 'AccountCertificateController');

    Route::post('certificate-template', [AccountCertificateController::class,'certificateTemplate'])->name('certificate.template');

    Route::post('account-certificate-preview/', 'AccountCertificateController@certificatePreview')->name('account-certificates.preview');
    Route::post('account-certificate-delete', 'AccountCertificateController@certificateDelete')->name('account-certificates.delete');

    Route::get('account-certificate-currentProfit/', 'AccountCertificateController@certificateCurrentProfit')->name('account-certificates.currentProfit');


    Route::post('accountwise-allsubscription', [AccountCertificateController::class, 'getSubscription'])->name('getAllSubscription');

    // News Calendar
    Route::delete('news-calendars/destroy', 'NewsCalendarController@massDestroy')->name('news-calendars.massDestroy');
    Route::post('news-calendars/parse-csv-import', 'NewsCalendarController@parseCsvImport')->name('news-calendars.parseCsvImport');
    Route::post('news-calendars/process-csv-import', 'NewsCalendarController@processCsvImport')->name('news-calendars.processCsvImport');
    Route::resource('news-calendars', 'NewsCalendarController');

    Route::post('news-calendar/makeRestricted', 'NewsCalendarController@massRestricted')
    ->name('news.makeRestricted');

    Route::get('news-calendar/unrestrict/{id}', 'NewsCalendarController@unRestrictNews')
    ->name('news.unRestrictNews');

    Route::post('news-calendar/makeUnRestricted', 'NewsCalendarController@massUnRestricted')
    ->name('news.makeUnRestricted');


    //chart Route
    Route::get('charts/showCharts', 'HomeController@showCharts')->name('charts.showCharts');


    Route::get('disable-trading/{id}', [AccountController::class,'disableTrading'])->name('account.disableTrading');
    Route::get('enable-trading/{id}', [AccountController::class,'enableTrading'])->name('account.enableTrading');


    //Account Settings
    Route::get('account/settings/{id}', [AccountController::class,'accountSettings'])->name('account-settings.view');

    Route::post('account/settings/deposit', [AccountController::class,'balanceDeposit'])->name('account-settings-deposit.view');

    Route::post('account/settings/withdraw', [AccountController::class,'balanceWithdraw'])->name('account-settings-withdraw.view');

    Route::post('account/settings/group-change', [AccountController::class,'groupChange'])->name('account-settings-groupChange.view');

    Route::post('account/settings/reset-metric', [AccountController::class,'resetMetric'])->name('account-settings-reset-metric.view');

    Route::get('account/news-trades/{id}', [NewsController::class,'newsTradeCheckView'])->name('account-news-trades.view');

    Route::get('specific/account/news-trades/{id}', [NewsController::class,'specificNewsTradeCheckView'])->name('account-specific-news-trades.view');


    // Account Status
    Route::delete('account-statuses/destroy', 'AccountStatusController@massDestroy')->name('account-statuses.massDestroy');
    Route::resource('account-statuses', 'AccountStatusController');

    // Account Status Messages
    Route::delete('account-status-messages/destroy', 'AccountStatusMessagesController@massDestroy')->name('account-status-messages.massDestroy');
    Route::post('account-status-messages/media', 'AccountStatusMessagesController@storeMedia')->name('account-status-messages.storeMedia');
    Route::post('account-status-messages/ckmedia', 'AccountStatusMessagesController@storeCKEditorImages')->name('account-status-messages.storeCKEditorImages');
    Route::resource('account-status-messages', 'AccountStatusMessagesController');

    // Account Status Log
    Route::delete('account-status-logs/destroy', 'AccountStatusLogController@massDestroy')->name('account-status-logs.massDestroy');
    Route::post('account-status-logs/media', 'AccountStatusLogController@storeMedia')->name('account-status-logs.storeMedia');
    Route::post('account-status-logs/ckmedia', 'AccountStatusLogController@storeCKEditorImages')->name('account-status-logs.storeCKEditorImages');
    Route::resource('account-status-logs', 'AccountStatusLogController');



    // Labels
    Route::delete('labels/destroy', 'LabelsController@massDestroy')->name('labels.massDestroy');
    Route::resource('labels', 'LabelsController');

    // Account Labels
    Route::delete('account-labels/destroy', 'AccountLabelsController@massDestroy')->name('account-labels.massDestroy');
    Route::resource('account-labels', 'AccountLabelsController');

    Route::get('account-labels/create/{id}', [AccountLabelsController::class,'storeAccountLabel'])->name('create.account-labels-account-id-wise');

    // Typeform
    Route::delete('typeforms/destroy', 'TypeformController@massDestroy')->name('typeforms.massDestroy');
    Route::post('typeforms/media', 'TypeformController@storeMedia')->name('typeforms.storeMedia');
    Route::post('typeforms/ckmedia', 'TypeformController@storeCKEditorImages')->name('typeforms.storeCKEditorImages');
    Route::post('typeforms/show-history', 'TypeformController@showHistory')->name('typeforms.showHistory');
    Route::resource('typeforms', 'TypeformController');

    Route::get('typeform/archived-payments', 'TypeformController@archivedPayments')->name('typeforms.archivedPayments');


    Route::post('webhook/topup-reset-request', [TypeformController::class,'webhookTopupReset'])->name('webhook.topupReset');

    Route::post('webhook/create-subscription', [TypeformController::class,'webhookNewAccount'])->name('webhook.newAccount');
    Route::post('manually-created', [TypeformController::class,'manuallyCreated'])->name('manuallyCreated');
    Route::post('change/status', [TypeformController::class,'changeManualPaymentStatus'])->name('change.typeForm.status');
    Route::get('change/fundingAmount', [TypeformController::class,'fundingAmount'])->name('webhook.fundingAmount');
    Route::post('change/transaction-id', [TypeformController::class,'transactionIdUpdate'])->name('webhook.updateTransaction');
    Route::get('change/remarksUpdate', [TypeformController::class,'remarksUpdate'])->name('webhook.remarksUpdate');
    Route::get('unarchieveOutsidePayment/{id}', [TypeformController::class,'unarchieveOutsidePayment'])->name('unarchieveOutsidePayment');

    Route::post('download/downloadOutsidePayment', 'TypeformController@downloadOutsidePayment')->name('typeform.downloadOutsidePayment');

    Route::post('download/downloadArchivedPayment', 'TypeformController@downloadArchivedPayment')->name('typeform.downloadArchivedPayment');



   // Utility Category
    Route::post('utility-categories/parse-csv-import', 'UtilityCategoryController@parseCsvImport')->name('utility-categories.parseCsvImport');
    Route::post('utility-categories/process-csv-import', 'UtilityCategoryController@processCsvImport')->name('utility-categories.processCsvImport');
    Route::resource('utility-categories', 'UtilityCategoryController', ['except' => ['destroy']]);

    // Utility Item
    Route::delete('utility-items/destroy', 'UtilityItemController@massDestroy')->name('utility-items.massDestroy');
    Route::post('utility-items/parse-csv-import', 'UtilityItemController@parseCsvImport')->name('utility-items.parseCsvImport');
    Route::post('utility-items/process-csv-import', 'UtilityItemController@processCsvImport')->name('utility-items.processCsvImport');
    Route::resource('utility-items', 'UtilityItemController');


    // Business Model
    Route::resource('business-models', 'BusinessModelController', ['except' => ['destroy']]);

    // Model Varient
    Route::resource('model-varients', 'ModelVarientController', ['except' => ['destroy']]);

    // Product
    Route::delete('products/destroy', 'ProductController@massDestroy')->name('products.massDestroy');
    Route::resource('products', 'ProductController');

    // Product Details
    Route::delete('product-details/destroy', 'ProductDetailsController@massDestroy')->name('product-details.massDestroy');
    Route::post('product-details/media', 'ProductDetailsController@storeMedia')->name('product-details.storeMedia');
    Route::post('product-details/ckmedia', 'ProductDetailsController@storeCKEditorImages')->name('product-details.storeCKEditorImages');
    Route::resource('product-details', 'ProductDetailsController');

    // Product Label
    Route::delete('product-labels/destroy', 'ProductLabelController@massDestroy')->name('product-labels.massDestroy');
    Route::post('product-labels/media', 'ProductLabelController@storeMedia')->name('product-labels.storeMedia');
    Route::post('product-labels/ckmedia', 'ProductLabelController@storeCKEditorImages')->name('product-labels.storeCKEditorImages');
    Route::resource('product-labels', 'ProductLabelController');

    // Coupon
    Route::delete('coupons/destroy', 'CouponController@massDestroy')->name('coupons.massDestroy');
    Route::post('coupons/media', 'CouponController@storeMedia')->name('coupons.storeMedia');
    Route::post('coupons/ckmedia', 'CouponController@storeCKEditorImages')->name('coupons.storeCKEditorImages');
    Route::post('coupons/parse-csv-import', 'CouponController@parseCsvImport')->name('coupons.parseCsvImport');
    Route::post('coupons/process-csv-import', 'CouponController@processCsvImport')->name('coupons.processCsvImport');
    Route::resource('coupons', 'CouponController');

    // Orders
    Route::get('orders/get-customer-info', 'OrderController@customerInfo')->name('orders.customer-info');
    Route::get('orders/coupon-info', 'OrderController@couponInfo')->name('orders.coupon-info');
    Route::get('orders/transaction-id', 'OrderController@transactionIdVerify')->name('orders.transaction-id');
    Route::post('orders/create', 'OrderController@orderCreate')->name('orders.create');
    Route::resource('orders', 'OrderController');


    //Refund

    Route::resource('refunds', 'RefundRequestController');
    Route::get('refunds/create/{id}', 'RefundRequestController@create')->name('refunds.create');
    Route::post('refunds/request', 'RefundRequestController@callRefundReqApi')->name('refunds.request');


    //Add Charge

    Route::resource('charges', 'AddExtraChargeController');
    Route::get('add-charge/{id}', 'AddExtraChargeController@create')->name('charges.create');
    Route::post('add-charge/request', 'AddExtraChargeController@callAddChargeApi')->name('charges.request');

    //Pageination Route For  Account Trade
    Route::post('accounts/id-wise-account-trades-page/{id}', 'AccountController@getAccountTradesPagination')->name('accounts.tradesPage');

    // All trade close for breached account
    Route::get('accounts/breached-account-all-trade-close/{id}', 'AccountController@breachedAccountAllTradeClose')->name('accounts.breachedAccountAllTradeClose');

    // DownloadManager

    Route::get('/download-manager', 'DownloadManagerController@index')->name('download-manager.index');
    Route::get('download-manager/generate-csv', 'DownloadManagerController@generateCSV')->name('download-manager.generate');
    Route::delete('download-manager/generated-csv-files/{id}', 'DownloadManagerController@generatedCSVDelete')->name('download-manager.generated-file-delete');

    // Payment method
    Route::resource('payment-method', 'PaymentMethodController');

    Route::get('payment-method-review', [PaymentMethodApprovalController::class,'index'])->name('payment-method-review.index');
    Route::get('payment-method-review/{payment_method}/edit', [PaymentMethodApprovalController::class,'edit'])->name('payment-method-review.edit');
    Route::put('payment-method-review/{payment_method}/update', [PaymentMethodApprovalController::class,'update'])->name('payment-method-review.update');
    Route::get('payment-method-review/{payment_method}', [PaymentMethodApprovalController::class,'show'])->name('payment-method-review.show');
    Route::get('/payment-method-order', 'PaymentMethodController@paymentMethodOrder')->name("payment_method.order");
    Route::post('/payment-method-order-update', 'PaymentMethodController@paymentMethodOrderUpdate')->name("payment-method-order.update");

    Route::get('country-list', [CountryCategoryController::class,'index'])->name('payment_method.country_list.index');
    Route::get('country-category-edit/{country}', [CountryCategoryController::class,'edit'])->name('payment_method.country_list.edit');
    Route::put('country-category-swap/{country}', [CountryCategoryController::class,'swapCategory'])->name('payment_method.country_category.swap');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
