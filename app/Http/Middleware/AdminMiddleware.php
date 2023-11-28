<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
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
        auth()->shouldUse('admin');
        if (auth()->check()) {
            return $next($request);
        }
        return redirect()->route('admin.auth.login');
    }
}
