<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiEncrypt
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

        if ($request->header('authorization') !== null) {
            $auth = env('API_USER') . ':' . env('API_PASSWORD');
            $str = 'Basic ' . base64_encode($auth);
            $head = $request->header('Authorization');
            if ($str != $head) {
                return response()->json(['message' => "Unauthorize User"], 401);
            }

        } else {
            return redirect('/admin');

        }
        return $next($request);

    }
}
