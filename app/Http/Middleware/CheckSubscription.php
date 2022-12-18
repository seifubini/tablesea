<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Package;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::user()->user_type == 'Restaurant' || Auth::user()->user_type == 'Manager' || Auth::user()->user_type == 'Booking_Manager'){
            if(Auth::user()->user_type == 'Manager' || Auth::user()->user_type == 'Booking_Manager')
            {
                $info = DB::table('users')
                    ->where('id', Auth::user()->id)
                    ->first();

                $user_id = $info->created_by;
            }
            elseif(Auth::user()->user_type == 'Restaurant')
            {
                $user_id = Auth::user()->id;
            }
            
            $package = DB::table('packages')->where('user_id', $user_id)->first();
            if($package != "")
            {
                $status = $package->subscription_status;
            }
            else{
                $status = "";
            }
            
            if($status == "Active")
            {
                return $next($request);
            }
            elseif($status == "Expired")
            {
                return redirect(RouteServiceProvider::EXPIRED);
            }
            elseif($status == "")
            {
                return $next($request);
            }
            else
            {
                return response()->json('Your account is inactive, contact the Administrator.');
            }
        }

        return $next($request);
    }
}
