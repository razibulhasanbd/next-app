<?php

namespace App\Http;

use App\Http\Middleware\ApiEncrypt;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{

    protected function bootstrappers()
    {
        return array_merge(
            [\Bugsnag\BugsnagLaravel\OomBootstrapper::class],
            parent::bootstrappers(),
        );
    }

    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ];

    protected $routeMiddleware = [
        'auth'                => \App\Http\Middleware\Authenticate::class,
        'auth.basic'          => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers'       => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can'                 => \Illuminate\Auth\Middleware\Authorize::class,
        'guest'               => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm'    => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed'              => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle'            => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified'            => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'api_auth'            => \App\Http\Middleware\ApiEncrypt::class,
        'horizon_auth'        => \App\Http\Middleware\AuthenticateHorizon::class,
        'custom_client_auth'  => \App\Http\Middleware\CustomClientAuth::class,
        'custom_account_auth' => \App\Http\Middleware\CustomerAccountAuth::class,
        'cors' => \App\Http\Middleware\Cors::class,
    ];

    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            // \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\AuthGates::class,
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\ApprovalMiddleware::class,


        ],
        'api' => [
            'throttle:10000,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,


        ],
    ];
}
