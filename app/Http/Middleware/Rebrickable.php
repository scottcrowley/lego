<?php

namespace App\Http\Middleware;

use Closure;

class Rebrickable
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
        if (! auth()->check()) {
            return redirect('login');
        }

        if (auth()->check() && auth()->user()->validCredentials()) {
            return $next($request);
        }

        return redirect(route('profile'))->with('message', 'Valid Rebrickable credentials are required.');
    }
}
