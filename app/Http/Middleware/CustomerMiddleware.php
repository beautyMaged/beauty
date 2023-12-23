<?php

namespace App\Http\Middleware;

use Brian2694\Toastr\Facades\Toastr;
use Closure;
use Illuminate\Support\Facades\Auth;
use function App\CPU\translate;

class CustomerMiddleware
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
        auth()->shouldUse('customer');
        if (auth()->check() && auth()->user()->is_active)
            return $next($request);
        elseif (auth()->check())
            auth()->logout();
        Toastr::info(translate('login_first_for_next_steps'));
        return redirect()->route('customer.auth.login');
    }
}
