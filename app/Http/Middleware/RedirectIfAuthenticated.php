<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Session;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
                return redirect()->route('operator.dashboard');
        }
        switch ($guard){
            case 'reviewerusulan':
                if (Auth::guard($guard)->check()) {
                    return redirect()->route('reviewer_usulan.dashboard');
                }
                break;

            default:
                if (Auth::guard($guard)->check()) {
                    return redirect()->route('operator.dashboard');
                }
                break;
        }
        return $next($request);
    }
}
