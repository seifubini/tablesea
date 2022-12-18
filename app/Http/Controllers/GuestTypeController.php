<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Restaurant;
use App\Models\GuestType;

class GuestTypeController extends Controller
{
    public function show_guest_types()
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

        $guest_types = DB::table('guest_types')
            ->join('restaurants', 'restaurants.id', '=', 'guest_types.restaurant_id')
            ->select('guest_types.id', 'guest_types.guest_type_name',
                'guest_types.Restaurant_name', 'guest_types.restaurant_id',
                'guest_types.created_at')
            ->where('restaurants.user_id', $user_id)
            ->get();

        $restaurants = DB::table('restaurants')
            ->where('user_id', $user_id)
            ->get();

        return view('Admin.Restaurant.GuestType', compact('guest_types', 'restaurants'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_type_name' => 'required|string',
            'restaurant_id' => 'required|int'
        ]);

        $restaurant_id = $request->restaurant_id;

        $restaurant = DB::table('restaurants')
            ->select('Restaurant_name')
            ->where('id', $restaurant_id)
            ->first();

        $restaurant_name = $restaurant->Restaurant_name;

        $info = new GuestType;

        $info->guest_type_name = $request->guest_type_name;
        $info->Restaurant_name = $restaurant_name;
        $info->restaurant_id = $restaurant_id;

        $info->save();

        return redirect()->back()->with('success', 'Guest Type created successfully.');

    }
    
    public function delete_guest($id)
    {
        if(Auth::user()->user_type == 'Restaurant' || Auth::user()->user_type == 'Administrator')
        {

            DB::table('guest_types')->delete($id);

            return redirect()->back()
                ->with('success','Restaurant Membership deleted successfully');

        }
        else{
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }

    }

}
