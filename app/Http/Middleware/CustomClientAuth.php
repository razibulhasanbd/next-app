<?php

namespace App\Http\Middleware;

use App\Helper\CustomAuthHelper;
use App\Services\ResponseService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CustomClientAuth
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
        if($request->bearerToken()){
            $userId = (Redis::connection('token'))->get($request->bearerToken());
            if($userId != null){
                CustomAuthHelper::setCustomer($userId);
                return $next($request);
            }
        }
        return ResponseService::apiResponse(401, "Unauthorized User");
    }

}
