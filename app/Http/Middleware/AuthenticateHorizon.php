<?php

namespace App\Http\Middleware;

use App\Constants\AppConstants;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthenticateHorizon
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $passed = false;

        $username = $request->header('PHP_AUTH_USER');
        $password = $request->header('PHP_AUTH_PW');
        if ($username && $password) {
            $horizonUsername = config(AppConstants::HORIZON_AUTH_USERNAME_CONFIG_KEY);
            $horizonPassword = config(AppConstants::HORIZON_AUTH_PASSWORD_CONFIG_KEY);

            if ($horizonUsername &&
                $horizonPassword &&
                $username === $horizonUsername &&
                $password === $horizonPassword) {
                $passed = true;
            }
        }

        if ($passed === false) {
            throw new UnauthorizedHttpException('Basic', 'Invalid credentials.');
        }

        return $next($request);
    }
}
