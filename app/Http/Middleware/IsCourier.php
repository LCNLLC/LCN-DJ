<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsCourier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->user_type == 'courier') {
            return $next($request);
        }
        else{
            abort(404);
        }
    }
}
