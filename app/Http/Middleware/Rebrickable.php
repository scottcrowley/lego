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

        if (auth()->check() && auth()->user()->validAPI()) {
            return $next($request);
        }

        return redirect(route('profiles'))->with('message', 'A Rebrickable API key is required.');
    }
}
