<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Restaurant;
use App\Models\RestaurantMembership;

class RestaurantMembershipController extends Controller
{
    public function show_memberships()
    {
        if(Auth::user()->user_type == 'Manager' || Auth::user()->user_type == 'Booking_Manager')
        {
            $info = DB::table('users')
                ->where('id', Auth::user()->id)
                ->first();
            $user_id = $info->created_by;

        }
        elseif(Auth::user()->user_type == 'Restaurant' || Auth::user()->user_type == 'Administrator')
        {
            $user_id = Auth::user()->id;

        }

        $memberships = DB::table('restaurant_memberships')
            ->join('restaurants', 'restaurants.id', '=', 'restaurant_memberships.restaurant_id')
            ->select('restaurant_memberships.id', 'restaurant_memberships.membership_name',
                'restaurant_memberships.Restaurant_name', 'restaurant_memberships.restaurant_id',
                'restaurant_memberships.created_at')
            ->where('restaurants.user_id', $user_id)
            ->get();

        $restaurants = DB::table('restaurants')
            ->where('user_id', $user_id)
            ->get();

        return view('Admin.Restaurant.memberships', compact('memberships', 'restaurants'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function store(Request $request)
    {
        $request->validate([
            'membership_name' => 'required|string',
            'restaurant_id' => 'required|int'
        ]);

        $restaurant_id = $request->restaurant_id;

        $restaurant = DB::table('restaurants')
            ->select('Restaurant_name')
            ->where('id', $restaurant_id)
            ->first();

        $restaurant_name = $restaurant->Restaurant_name;

        $info = new RestaurantMembership;

        $info->membership_name = $request->membership_name;
        $info->restaurant_id = $restaurant_id;
        $info->Restaurant_name = $restaurant_name;

        $info->save();

        return redirect()->back()->with('success', 'Membership created successfully.');

    }
    
    public function delete_membership($id)
    {
        if(Auth::user()->user_type == 'Restaurant' || Auth::user()->user_type == 'Administrator')
        {

            DB::table('restaurant_memberships')->delete($id);

            return redirect()->back()
                ->with('success','Restaurant Membership deleted successfully');

        }
        else{
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }

    }

}
