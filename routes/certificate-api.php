<?php

use App\Http\Controllers\Api\V1\Admin\CertificateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/cert', function () {
    return view('welcome');
});
//Route::get('user/certificate/{login_id}', [CertificateController::class, 'getCertificates']);
Route::get('user/certificate', [CertificateController::class, 'getCertificates']);
Route::get('user/trading-data', [CertificateController::class, 'getTradingData']);
Route::get('user/account-status', [CertificateController::class, 'getAccountStatus']);
Route::get('user/public-account-status', [CertificateController::class, 'getPublicAccountStatus']);
Route::patch('update-share-certificate', [CertificateController::class, 'updateShare']);
Route::post('certificate/{type}', [CertificateController::class, 'generateCertificate']);
Route::patch('update-toggle-info', [CertificateController::class, 'updateToggleInfo']);



//Route::middleware('auth:api')->group(function () {
//    Route::middleware('user.active')->group(function () {

//});


