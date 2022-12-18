<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if(Auth::user()->user_type == 'Client'){
                    return redirect(RouteServiceProvider::HOME);
                }
                elseif(Auth::user()->user_type == 'Restaurant' || Auth::user()->user_type == 'Booking_Manager' || Auth::user()->user_type == 'Manager'){
                    return redirect(RouteServiceProvider::BACKEND);
                }
                elseif(Auth::user()->user_type == 'Administrator'){
                    return redirect(RouteServiceProvider::ADMIN);
                }
            }
        }

        return $next($request);
    }
}
