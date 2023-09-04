<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SellerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        auth()->shouldUse('seller');
        if (auth()->check() && auth()->user()->status == 'approved')
            return $next($request);
        auth()->guard()->logout();
        return redirect()->route('seller.auth.login');
    }
}
