<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Package;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(Auth::user()->user_type == "Administrator")
        {
            $all_packages = DB::table('packages')->where('subscription_status', 'Active')->get();

            foreach($all_packages as $package)
            {
                  $date1 = time();
                  $date2 = strtotime($package->subscription_expire_date);
                  $date_diff = $date2 - $date1;
                  $date_diff = $date_diff / (60 * 60 * 24);
                  if($date_diff > 1)
                  {
                    $diff = round($date_diff);
                  }
                  elseif($date_diff < 1 && $date_diff > 0)
                  {
                    $diff = 1;
                  }
                  elseif($date_diff == 0)
                  {
                    $diff = 0;
                  }
                  else
                  {
                    $diff = 0;
                  }

                  if($diff == 0)
                  {
                    $update = DB::table('packages')
                        ->where('id', $package->id)
                        ->update([
                            'subscription_status' => 'Expired'
                        ]);
                  }
                  elseif($diff > 0)
                  {
                    $update = DB::table('packages')
                        ->where('id', $package->id)
                        ->update([
                            'subscription_status' => 'Active'
                        ]);
                  }

            }

            $subscription = DB::table('packages')
                ->where('user_id', Auth::user()->id)
                ->first();

            $packages = Package::All();
            $restaurants = DB::table('restaurants')
                        ->join('users', 'users.id', '=', 'restaurants.user_id')
                        ->select('restaurants.id', 'restaurants.Restaurant_name', 'users.name',
                            'restaurants.Restaurant_address', 'restaurants.user_id')
                        ->get();

            return view('Admin.Package.index', compact('packages', 'restaurants', 'subscription'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
        }
        else
        {
            return redirect()->back()->with('error', "you don't have enough permission to access this page, contact your administrator.");
        }
    }

    public function store(Request $request)
    {
        if(Auth::user()->user_type == "Administrator")
        {
            $data = $request->validate([
                'restaurant_id' => 'required|int',
                'subscription_start_date' => 'required|date',
                'subscription_expire_date' => 'required|date',
                'created_by' => 'required|int'
            ]);

            if($request->subscription_status == "on")
            {
                $subscription_status = 'Active';
            }
            else
            {
                $subscription_status = "Expired";
            }

            $restaurant_id = $request->restaurant_id;
            $restaurant = DB::table('restaurants')->where('id', $restaurant_id)->first();
            $user_id = $restaurant->user_id;

            $subscription = DB::table('packages')
                ->where('user_id', $user_id)
                ->where('subscription_status', 'Active')
                ->first();

            if(is_null($subscription))
            {
                $subscription_code = Str::random(20);

                $subscribed = DB::table('packages')
                    ->where('subscription_code', $subscription_code)
                    ->where('subscription_status', 'Active')
                    ->first();

                if($subscribed)
                {
                    return redirect()->back()->with('error', 'this subscription code already exists, refresh the page and try again. If the issue still persists contact Administrator.');
                }
                else
                {
                    $expire_date = $request->subscription_expire_date;
                    $subscription_expire_date = new DateTime($expire_date);

                    $start_date = $request->subscription_start_date;
                    $subscription_start_date = new DateTime($start_date);

                    $days = $subscription_start_date->diff($subscription_expire_date);
                    $number_of_days = $days->format('%a');

                    $restaurant = DB::table('restaurants')->where('id', $restaurant_id)->first();
                    $restaurant_name = $restaurant->Restaurant_name;
                    $restaurant_email = $restaurant->Restaurant_email;
                    $user_id = $restaurant->user_id;

                    $user = DB::table('users')->where('id', $user_id)->first();
                    $user_name = $user->name;
                    $user_email = $user->email;

                    $info = new Package;

                    $info->user_id = $user_id;
                    $info->user_name = $user_name;
                    $info->restaurant_id = $restaurant_id;
                    $info->subscription_start_date = $request->subscription_start_date;
                    $info->subscription_expire_date = $request->subscription_expire_date;
                    $info->number_of_days = $number_of_days;
                    $info->restaurant_name = $restaurant_name;
                    $info->user_email = $user_email;
                    $info->subscription_code = $subscription_code;
                    $info->created_by = $request->created_by;
                    $info->subscription_status = $subscription_status;

                    $info->save();

                    return redirect()->back()->with('success', 'subscription created successfully.');
                }
            }
            else
            {
                return redirect()->back()->with('error', 'this user has an active subscription, you cannot add another subscription before it expire.');
            }
        }
        else
        {
            return redirect()->back()->with('error', "you don't have enough permission to access this page, contact your administrator.");
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Package $package)
    {
        if(Auth::user()->user_type == "Administrator")
        {
            $data = $request->validate([
                    'restaurant_id' => 'required|int',
                    'subscription_start_date' => 'required|date',
                    'subscription_expire_date' => 'required|date',
                    'created_by' => 'required|int',
                    'identity' => 'required|int',
                    'updated_by' => 'required|int'
                ]);

            $id = $request->identity;

            if($request->subscription_status == "on")
            {
                $subscription_status = 'Active';
            }
            else
            {
                $subscription_status = "Expired";
            }

            $expire_date = $request->subscription_expire_date;
            $subscription_expire_date = new DateTime($expire_date);

            $start_date = $request->subscription_start_date;
            $subscription_start_date = new DateTime($start_date);

            $days = $subscription_start_date->diff($subscription_expire_date);
            $number_of_days = $days->format('%a');

            DB::table('packages')
                ->where('id', $id)
                ->update([
                    'subscription_start_date' => $subscription_start_date,
                    'subscription_expire_date' => $subscription_expire_date,
                    'number_of_days' => $number_of_days,
                    'subscription_status' => $subscription_status,
                    'updated_by' => $request->updated_by
                ]);

            return redirect()->back()->with('success', 'subscription updated successfully.');
        }
        else
        {
            return redirect()->back()->with('error', "you don't have enough permission to access this page, contact your administrator.");
        }
    }
}
