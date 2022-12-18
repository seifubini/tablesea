<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Table;
use App\Models\Restaurant;
use App\Models\Reservation;
use App\Models\Guest;

class GuestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function guests($id)
    {
        if(Auth::user()->user_type == 'Manager' || Auth::user()->user_type == 'Booking_Manager')
        {
            $info = DB::table('users')
                ->where('id', Auth::user()->id)
                ->first();

            $user_id = $info->created_by;

            $restaurants = DB::table('restaurants')
                ->join('restaurant__users', 'restaurant__users.restaurant_id', '=', 'restaurants.id')
                ->select('restaurants.id', 'restaurants.Restaurant_name')
                ->where('restaurant__users.user_id', Auth::user()->id)
                ->get();

        }
        elseif(Auth::user()->user_type == 'Restaurant' || Auth::user()->user_type == 'Administrator')
        {
            $user_id = Auth::user()->id;

            $restaurants = DB::table('restaurants')
                ->where('user_id', '=', Auth::user()->id)
                ->get();
        }

        $restaurant_id = $id;

        $guest_types = DB::table('guest_types')
            ->where('restaurant_id', $restaurant_id)
            ->get();

        $restaurant = DB::table('restaurants')
            ->where('id', '=', $restaurant_id)
            ->first();

        $guests = DB::table('guests')
            ->join('reservations', 'reservations.user_email', '=', 'guests.email')
            ->select('guests.id', 'guests.user_name', 'guests.email', 'guests.phone_number', 'guests.restaurant_id', 'guests.user_tag',
                'guests.guest_title', 'guests.guest_note', 'guests.membership_tag', 'guests.created_at',
                DB::raw("count(reservations.reservation_code) AS number_of_reservations"),
                DB::raw("sum(reservations.reservation_total_cost) AS total_spent"))
            ->where('guests.restaurant_id', $restaurant_id)
            ->where('reservations.restaurant_id', $restaurant_id)
            ->groupBy('guests.id', 'reservations.user_email', 'guests.email', 'guests.user_name', 'guests.phone_number', 'guests.user_tag', 'guests.guest_title', 'guests.membership_tag',
                'guests.guest_note', 'guests.restaurant_id', 'guests.created_at')
            ->get();

        /**$guests = DB::table('guests')
        ->join('reservations', 'reservations.user_email', '=', 'guests.email')
        ->select('guests.user_name', 'guests.email', 'guests.phone_number', 'guests.restaurant_id', 'guests.user_tag', 'guests.guest_title', 'guests.membership_tag',
        'guests.created_at')
        ->selectRaw('count(reservations.reservation_code) AS number_of_reservations')
        ->where('guests.restaurant_id', $restaurant_id)
        ->get();*/

        $memberships = DB::table('restaurant_memberships')
            ->where('restaurant_id', $restaurant_id)
            ->get();

        //var_dump($guests);
        //die();

        return view('Admin.Guest.index', compact('restaurants', 'restaurant', 'restaurant_id', 'guests', 'memberships', 'guest_types'))
            ->with('i', (request()->input('page', 1) - 1) * 5);

    }

    public function get_all_guests($id)
    {
        $guests = DB::table('guests')
            ->where('restaurant_id', $id)
            ->orderBy('user_name')
            ->get();

        $count = count($guests);
        $all_guests = array();

        for ($i = 0; $i < $count; $i++)
        {
            $all_guests[$i]['email'] = $guests[$i]->email;
        }

        return json_encode($all_guests);
    }

    public function find_guest(Request $request)
    {
        if($request->email != "" && $request->restaurant_id != "")
        {
            $email = $request->email;

            $guest = DB::table('guests')
                ->where('restaurant_id', $request->restaurant_id)
                ->where('email', 'LIKE', $email)
                ->first();

            return json_encode($guest);
        }

    }

    public function get_guests(Request $request)
    {
        $restaurant_id = $request->restaurant_id;
        $guests = DB::table('guests')
            ->where('restaurant_id', $restaurant_id)
            ->orderBy('user_name')
            ->get();

        echo json_encode($guests);

    }

    public function show(Guest $guest)
    {

    }

    public function store(Request $request)
    {
        if(Auth::user()->user_type == 'Restaurant' || Auth::user()->user_type == 'Manager')
        {
            $request->validate([
                'user_name' => 'required|string|max:50',
                'phone_number' => 'required|string|max:50',
                'email' => 'required|email|max:50',
                'restaurant_id' => 'required|int',
                'membership_tag' => 'required|string'
            ]);

            $restaurant_id = $request->restaurant_id;

            if($request->guest_note)
            {
                $request->validate(['guest_note' => 'required|string|max:500']);
                $guest_note = $request->guest_note;
            }
            else{
                $guest_note = "";
            }

            $user = DB::table('guests')
                ->where('email', $request->user_email)
                ->where('restaurant_id', $restaurant_id)
                ->first();

            if(is_null($user))
            {
                $data = new Guest;

                $data->user_name = $request->user_name;
                $data->email = $request->email;
                $data->phone_number = $request->phone_number;
                $data->guest_title = $request->guest_title;
                $data->guest_note = $guest_note;
                $data->user_tag = $request->user_tag;
                $data->restaurant_id = $restaurant_id;
                $data->membership_tag = $request->membership_tag;
                $data->save();

                return redirect()->route('guests', $restaurant_id)->with('success','New Guest added Successfully');
            }
            else{
                return redirect()->route('guests', $restaurant_id)->with('success','Guest already Exists.');
            }

        }
        else{
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }

    }

    //get number of total reservations
    public function get_reservations(Request $request)
    {
        $id = $request->id;
        $guest = DB::table('guests')
            ->where('id', $id)
            ->first();

        $guest_email = $guest->email;
        $restaurant_id = $request->restaurant_id;

        $reservations = DB::table('reservations')
            ->where('user_email', $guest_email)
            ->where('restaurant_id', $restaurant_id)
            ->get();

        echo json_encode($reservations);
    }

    //get number of upcoming reservations
    public function get_upcoming(Request $request)
    {
        $id = $request->id;
        $guest = DB::table('guests')
            ->where('id', $id)
            ->first();

        $guest_email = $guest->email;
        $restaurant_id = $request->restaurant_id;

        $upcoming = DB::table('reservations')
            ->where('user_email', $guest_email)
            ->where('restaurant_id', $restaurant_id)
            ->where('reservation_status', '=', 'booked')
            ->get();

        echo json_encode($upcoming);
    }

    //get number of cancelled reservations
    public function get_cancelled(Request $request)
    {
        $id = $request->id;
        $guest = DB::table('guests')
            ->where('id', $id)
            ->first();

        $guest_email = $guest->email;
        $restaurant_id = $request->restaurant_id;

        $cancelled = DB::table('reservations')
            ->where('user_email', $guest_email)
            ->where('restaurant_id', $restaurant_id)
            ->where('reservation_status', '=', 'cancelled')
            ->get();

        echo json_encode($cancelled);
    }

    //get number of denied reservations
    public function get_denied(Request $request)
    {
        $id = $request->id;
        $guest = DB::table('guests')
            ->where('id', $id)
            ->first();

        $guest_email = $guest->email;
        $restaurant_id = $request->restaurant_id;

        $denied = DB::table('reservations')
            ->where('user_email', $guest_email)
            ->where('restaurant_id', $restaurant_id)
            ->where('reservation_status', '=', 'Completed')
            ->get();

        echo json_encode($denied);
    }

    //get number of covered seats
    public function get_cover(Request $request)
    {
        $id = $request->id;
        $guest = DB::table('guests')
            ->where('id', $id)
            ->first();

        $guest_email = $guest->email;
        $restaurant_id = $request->restaurant_id;

        $covered = DB::table('reservations')
            ->select('number_of_people')
            ->where('user_email', $guest_email)
            ->where('restaurant_id', $restaurant_id)
            ->where('reservation_status', '!=', 'booked')
            ->where('reservation_status', '!=', 'cancelled')
            ->sum('number_of_people');

        echo json_encode($covered);
    }

    //get total amount of money spend
    public function get_total_spend(Request $request)
    {
        $id = $request->id;
        $guest = DB::table('guests')
            ->where('id', $id)
            ->first();

        $guest_email = $guest->email;
        $restaurant_id = $request->restaurant_id;

        $total_spend = DB::table('reservations')
            ->select('reservation_total_cost')
            ->where('user_email', $guest_email)
            ->where('restaurant_id', $restaurant_id)
            ->where('reservation_status', '=', 'Completed')
            ->sum('reservation_total_cost');

        echo json_encode($total_spend);
    }

    public function update(Request $request, Guest $guest)
    {
        if(Auth::user()->user_type == 'Restaurant' || Auth::user()->user_type == 'Manager')
        {
            $request->validate([
                'user_name' => 'required|string',
                'phone_number' => 'required|string',
                'guest_title' => 'required|string',
                'membership_tag' => 'string|string'
            ]);
            $guest_id = $guest->id;
            if($request->guest_note)
            {
                $request->validate([ 'guest_note' => 'required|string|max:500' ]);
                $guest_note = $request->guest_note;
            }
            else{
                $guest_note = "";
            }
            $restaurant_id = $request->restaurant_id;
            $update = DB::table('guests')
                ->where('id', $guest_id)
                ->update([
                    'user_name' => $request->user_name,
                    'phone_number' => $request->phone_number,
                    'guest_title' => $request->guest_title,
                    'guest_note' => $guest_note,
                    'user_tag' => $request->guest_tag,
                    'membership_tag' => $request->membership_tag
                ]);
            if($update){
                return redirect()->route('guests', $restaurant_id)->with('success','Guest Information Updated Successfully');
            }
            else{
                return redirect()->route('guests', $restaurant_id)->with('error','Guest Information cannot be updated, check your inputs.');
            }
        }
        else{
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Guest $guest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Guest $guest)
    {
        if(Auth::user()->user_type == 'Restaurant' || Auth::user()->user_type == 'Manager')
        {
            $guest->delete();

            return redirect()->back()
                ->with('success','Guest Deleted successfully');

        }
        else{
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }
    }
}
