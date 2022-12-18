<?php

namespace App\Http\Controllers;

use App\Mail\NotifyMail;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\Guest;
use App\Models\Table;
use App\Models\Table_To_Reservation;
use App\Models\RestaurantMembership;
use App\Models\GuestType;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReservationController extends Controller
{

    public function index()
    {
        $ip = $_SERVER['REMOTE_ADDR'];

        $ipdat = @json_decode(file_get_contents(
            "http://www.geoplugin.net/json.gp?ip=" . $ip));

        $country = $ipdat->geoplugin_countryName;
        
        $featured_restaurants = DB::table('restaurants')
            ->where('featured', '=', 'yes')
            ->where('Restaurant_Country', 'LIKE', '%'.$country.'%')
            ->get();

        //$restaurants = Restaurant::All();

        $restaurants = DB::table('restaurants')
            ->where('Restaurant_Country', 'LIKE', '%'.$country.'%')
            ->get();

        $opening = '00:00:00';

        $open_hour = Carbon::parse($opening)->format('H:i:s');
        $start_hour = Carbon::createFromFormat('H:i:s', $open_hour)->subMinute(30);
        $start_hour = Carbon::parse($start_hour)->format('H:i:s');

        $working_hours = array();

        $max = 48;
        for($i=0; $i < $max; $i++)
        {
            $opening_hour = Carbon::createFromFormat('H:i:s',$start_hour)->addMinutes(30);
            //$opening_hour = date('h:i A', strtotime($opening_hour));
            $start_hour = Carbon::parse($opening_hour)->format('H:i:s');

            //$open_hour = $opening_hour;
            $working_hours[] = $start_hour;
        }

        return view('Reservation.index', compact('featured_restaurants', 'restaurants',
            'country', 'working_hours'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function all_restaurants()
    {
        //$restaurants = DB::table('restaurants')->paginate(5);
        $restaurants = Restaurant::All();

        $opening = '00:00:00';

        $open_hour = Carbon::parse($opening)->format('H:i:s');
        $start_hour = Carbon::createFromFormat('H:i:s', $open_hour)->subMinute(30);
        $start_hour = Carbon::parse($start_hour)->format('H:i:s');

        $working_hours = array();

        $max = 48;
        for($i=0; $i < $max; $i++)
        {
            $opening_hour = Carbon::createFromFormat('H:i:s',$start_hour)->addMinutes(30);
            //$opening_hour = date('h:i A', strtotime($opening_hour));
            $start_hour = Carbon::parse($opening_hour)->format('H:i:s');

            //$open_hour = $opening_hour;
            $working_hours[] = $start_hour;
        }

        return view('Reservation.all_restaurants', compact('restaurants', 'working_hours'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    //for search autocomplete
    public function get_all_restaurants()
    {
        $restaurants = DB::table('restaurants')
            ->select('Restaurant_name', 'Restaurant_address', 'Restaurant_Country', 'Restaurant_City', 'Restaurant_type')
            ->get();
        $count = count($restaurants);
        $all_restaurants = array();

        for ($i = 0; $i < $count; $i++)
        {
            $all_restaurants[$i]['name'] = $restaurants[$i]->Restaurant_name;
            $all_restaurants[$i]['address'] = $restaurants[$i]->Restaurant_address;
            $all_restaurants[$i]['country'] = $restaurants[$i]->Restaurant_Country;
            $all_restaurants[$i]['city'] = $restaurants[$i]->Restaurant_City;
            $all_restaurants[$i]['cuisine'] = $restaurants[$i]->Restaurant_type;

        }
        //var_dump(json_encode($all_restaurants));
        //die();
        return json_encode($all_restaurants);
    }

    public function store(Request $request)
    {
        
        if(Auth::check())
        {
            /**perform check on the time and then cancel a reservation if not confirmed
                $reservations = DB::table('reservations')
                    ->where('reservation_status', '=', 'booked')
                    ->where('time_of_reservation', '<',Carbon::parse('-48 hours'))
                    ->get();

                foreach ($reservations as $reservation)
                {
                    if($reservation->reservation_status != "confirmed")
                    {
                        $reservation_id = $reservation->id;

                        DB::table('reservations')
                            ->where('id', $reservation_id)
                            ->update([ 'reservation_status' => 'cancelled']);
                    }
                }*/
                
            $request->validate([
                'user_name' => 'required|string|max:50',
                'user_email' => 'required|email|max:50',
                'user_phone' => 'required|string|min:10|max:13',
                'number_of_people' => 'required',
                'time_of_reservation' => 'required|date_format:H:i:s',
                'date_of_reservation' => 'required|date|after:yesterday',
                'restaurant_id' => 'required|int',
                'reservation_status' => 'required|string',
                'reservation_type' => 'required|string',
                'created_by' => 'required|int',
                'creater_name' => 'required|string'
            ]);
            
            if($request->reservation_attachment != "")
            {
                $request->validate([
                    'reservation_attachment' => 'file|mimes:pdf,csv,jpeg,png,jpg|max:2048'
                ]);

                $reservation_attachment = $request->user_name.time().'.'.$request->reservation_attachment->extension();

                $request->reservation_attachment->move(public_path('images/attachments'), 
                    $reservation_attachment);
            }
            else
            {
                $reservation_attachment = "";
            }
            

            $restaurant = DB::table('restaurants')
                ->where('id', $request->restaurant_id)
                ->first();
                
            $total_reservations = DB::table('reservations')
            ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
            ->where('reservations.restaurant_id', $restaurant->id)
            ->where('reservations.date_of_reservation', $request->reservation_date)
            ->where('reservations.time_of_reservation', $request->reservation_time)
            ->get();
            
            $total_reservations = count($total_reservations);


            $rest_type = $restaurant->Reservation_update_type;

            if ($rest_type == 'Manual')
            {
                /**perform check on the time and then cancel a reservation if not confirmed
                $reservations = DB::table('reservations')
                    ->where('reservation_status', '=', 'booked')
                    ->where('time_of_reservation', '<',Carbon::parse('-48 hours'))
                    ->get();

                foreach ($reservations as $reservation)
                {
                    if($reservation->reservation_status != "confirmed")
                    {
                        $reservation_id = $reservation->id;

                        DB::table('reservations')
                            ->where('id', $reservation_id)
                            ->update([ 'reservation_status' => 'cancelled']);
                    }
                }*/

                if(Auth::user()->user_type == "Restaurant" || Auth::user()->user_type == "Manager" || Auth::user()->user_type == "Booking_Manager") {
                    $restaurant_id = $request->restaurant_id;
                    $restaurant = DB::table('restaurants')
                        ->where('id', $restaurant_id)
                        ->first();
                    $time = $request->time_of_reservation;
                    $date = $request->date_of_reservation;
                    $today = date('Y-m-d');
                    $now_time = Carbon::now()->format('H:i:s');

                    if($date == $today && $time < $now_time){
                        return redirect()->back()
                            ->with('error', 'You cannot reserve a past time.');
                    }

                    $day_name = Carbon::parse($date)->dayOfWeek;

                    if($restaurant->sunday_open == 'no' && $day_name == 0)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Sundays');
                    }
                    elseif($restaurant->monday_open == 'no' && $day_name == 1)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Mondays');
                    }
                    elseif($restaurant->tuesday_open == 'no' && $day_name == 2)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Tuesdays');
                    }
                    elseif($restaurant->wednesday_open == 'no' && $day_name == 3)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Wednesdays');
                    }
                    elseif($restaurant->thursday_open == 'no' && $day_name == 4)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Thursday');
                    }
                    elseif($restaurant->friday_open == 'no' && $day_name == 5)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Fridays');
                    }
                    elseif($restaurant->saturday_open == 'no' && $day_name == 6)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Saturdays');
                    }
                    
                    $restaurant_id = $request->restaurant_id;
                    $restaurant_name = $restaurant->Restaurant_name;
                    $reservation_code = Str::random(10);
                    $time = $request->time_of_reservation;
                    $date = $request->date_of_reservation;
                    $no_of_people = $request->number_of_people;
                    $table_max_cover = 0;
                    $table_min_cover = 0;
                    $table_total = 0;
                    $people = intval($request->number_of_people);
                    
                    if($request->table_id)
                    {
                        $table_ids = $request->table_id;
                        
                        $restaurant_id = $request->restaurant_id;
                        $restaurant_name = $restaurant->Restaurant_name;
                        $reservation_code = Str::random(10);
                        $time = $request->time_of_reservation;
                        $date = $request->date_of_reservation;
                        $no_of_people = $request->number_of_people;
                        $people = intval($request->number_of_people);
    
                        $table_id_count = count($table_ids);

                    for($i = 0; $i < $table_id_count; $i++)
                    {
                        $table_id = $table_ids[$i];
                        $table = DB::table('tables')->where('id', $table_id)->first();
                        //$table_price = intval($table->table_price);
                        //$table_total = $table_total + $table_price;

                        $max_cover = $table->max_covers;
                        $min_cover = $table->min_covers;
                        $table_max_cover = $table_max_cover + $max_cover;
                        $table_min_cover = $table_min_cover + $min_cover;

                        $get_table = DB::table('reservations')
                            ->join('table__to__reservations', 'table__to__reservations.reservation_code', '=', 'reservations.reservation_code')
                            ->join('tables', 'tables.id', '=', 'table__to__reservations.table_id')
                            ->where('table__to__reservations.table_id', $table_id)
                            ->where('reservations.date_of_reservation', '=', $date)
                            ->where('reservations.time_of_reservation', '=', $time)
                            ->where('reservations.reservation_status', 'confirmed')
                            ->first();

                        if(!is_null($get_table))
                        {
                            $table_reserved_for = $get_table->time_of_reservation;
                            $new_reservation = $request->time_of_reservation;
                            $table_duration = $get_table->reservation_duration;
                            $total_hour = Carbon::createFromFormat('H:i:s',$table_reserved_for)->addMinutes($table_duration);
                            $total_hour = Carbon::parse($total_hour)->format('H:i:s');

                            if($new_reservation < $total_hour)
                            {
                                return redirect()->back()
                                    ->with('error', 'Reservation not created. You cannot book a table for two different reservations at the same time and date or before the current reservation ends.');
                            }
                        }
                    }

                        //$total_price = $table_total * $people;
                        if( $no_of_people > $table_max_cover)
                        {
                            return redirect()->back()->with('error', 'Table capacity is less than the number of people selected.');
                        }
                        elseif ($no_of_people < $table_min_cover)
                        {
                            return redirect()->back()->with('error', 'Table capacity is not utilised properly.');
                        }
                        
                    }

                    $user = DB::table('guests')
                        ->where('email', $request->user_email)
                        ->where('restaurant_id', $restaurant_id)
                        ->first();

                    $closing_hour = $restaurant->restaurant_closing_hour;
                    $opening_hour = $restaurant->restaurant_opening_hour;
                    $restaurant_max_capacity = $restaurant->Restaurant_max_capacity;
                    $Restaurant_email = $restaurant->Restaurant_email;

                    /**$booked_seats = DB::table('reservations')
                    ->where('restaurant_id', $restaurant_id)
                    ->where('reservation_status', 'confirmed')
                    ->where('date_of_reservation', 'LIKE', $date)
                    ->where('time_of_reservation', 'LIKE', $time)
                    ->sum('number_of_people');*/

                    $booked_seats = DB::table('reservations')
                        ->join('table__to__reservations', 'table__to__reservations.reservation_code',
                            '=', 'reservations.reservation_code')
                        ->where('reservations.restaurant_id', $restaurant_id)
                        ->where('reservations.reservation_status', 'confirmed')
                        ->where('reservations.date_of_reservation', 'LIKE',$date)
                        ->where('reservations.time_of_reservation', 'LIKE',$time)
                        ->sum('reservations.number_of_people');

                    //$table = DB::table('tables')
                    //       ->where('id', $request->table_id)
                    //     ->first();

                    $table_identifier = $reservation_code;
                    $available_seats = $restaurant_max_capacity - $booked_seats;

                    $check_table = DB::table('reservations')
                        ->join('table__to__reservations', 'table__to__reservations.reservation_code',
                            '=', 'reservations.reservation_code')
                        ->join('tables', 'tables.id', '=', 'table__to__reservations.table_id')
                        ->where('table__to__reservations.table_id', $request->table_id)
                        ->where('reservations.date_of_reservation','LIKE', $date)
                        ->where('reservations.time_of_reservation','LIKE', $time)
                        ->where('tables.table_is_booked', 'yes')
                        ->get();

                    $count_check_table = count($check_table);

                    if($time < $opening_hour || $time > $closing_hour)
                    {
                        return redirect()->back()->with('error', 'Restaurant is Closed at the specified hour.');
                    }
                    elseif ($no_of_people > $available_seats)
                    {
                        return redirect()->back()->with('error', 'Number of People Exceeds the Capacity of Your Restaurant.');
                    }
                    elseif ($no_of_people > $restaurant_max_capacity)
                    {
                        return redirect()->back()->with('error', 'Number of People Exceeds the Capacity of Your Restaurant.');
                    }
                    elseif($count_check_table > 0)
                    {
                        return redirect()->back()->with('error', "'Table is booked at ' + $time + 'try other available times.'");
                    }
                    elseif($request->table_id){
                        for($i = 0; $i < $table_id_count; $i++)
                        {
                            $table_id = $table_ids[$i];
                            $table_to_reservation = new Table_To_Reservation;
                            $table_to_reservation->reservation_code = $reservation_code;
                            $table_to_reservation->table_id = $table_id;
                            $table_to_reservation->save();
                            DB::table('tables')
                                ->where('id', $table_id)
                                ->update([
                                    'table_is_booked'=>'yes'
                                ]);
                        }
                    }
                    if (is_null($user)) {
                        $data = new Guest;

                        $data->user_name = $request->user_name;
                        $data->email = $request->user_email;
                        $data->phone_number = $request->user_phone;
                        $data->restaurant_id = $restaurant_id;
                        $data->membership_tag = $request->membership_tag;
                        $data->save();

                        $guest_id = $data->id;

                        $info = new Reservation;

                        $info->user_name = $request->user_name;
                        $info->user_email = $request->user_email;
                        $info->user_phone = $request->user_phone;
                        $info->number_of_people = $request->number_of_people;
                        $info->time_of_reservation = $request->time_of_reservation;
                        $info->date_of_reservation = $request->date_of_reservation;
                        $info->reservation_duration = $request->reservation_duration;
                        $info->restaurant_id = $request->restaurant_id;
                        $info->restaurant_name = $restaurant_name;
                        $info->user_id = $guest_id;
                        $info->reservation_status = $request->reservation_status;
                        $info->reservation_tag = $request->reservation_tag;
                        $info->reservation_code = $reservation_code;
                        $info->reservation_type = $request->reservation_type;
                        $info->table_indentifier = $reservation_code;
                        $info->membership_tag = $request->membership_tag;
                        $info->hostess_note = $request->hostess_note;
                        $info->reserver_message = $request->reserver_message;
                        $info->reservation_attachment = $reservation_attachment;
                        $info->created_by = Auth::user()->id;
                        $info->creater_name = Auth::user()->name;

                        $info->save();
                        
                        $Additional_note = '';
                        
                        $restaurant_booking_managers = DB::table('restaurant__users')
                            ->join('users', 'users.id', '=', 'restaurant__users.user_id')
                            ->where('restaurant__users.restaurant_id', $restaurant_id)
                            ->where('restaurant__users.user_role', 'Booking_Manager')
                            ->get();
                            
                        foreach($restaurant_booking_managers as $booking_manager)
                        {
                            $booking_manager_email = $booking_manager->email;
                            
                            $data = [
                                'subject' => 'Reservation Update.',
                                'email' => $request->user_email,
                                'status' => $request->reservation_status,
                                'date' => $request->date_of_reservation,
                                'time' => $request->time_of_reservation,
                                'guest_name' => $request->user_name,
                                'reservation_code' => $reservation_code,
                                'Restaurant_name' => $restaurant_name,
                                'no_of_people' => $request->number_of_people,
                                'restaurant_id' => $restaurant->id,
                                'Restaurant_email' => $Restaurant_email,
                                'booking_manager_email' => $booking_manager_email,
                                'Additional_note' => $Additional_note
                            ];
                            
                            Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                                $message->to($data['booking_manager_email'])
                                    ->subject($data['subject']);
                            });
                        }
                
                        $restaurant_managers = DB::table('restaurant__users')
                            ->join('users', 'users.id', '=', 'restaurant__users.user_id')
                            ->where('restaurant__users.restaurant_id', $restaurant_id)
                            ->Where('restaurant__users.user_role', 'Manager')
                            ->get();

                        foreach($restaurant_managers as $manager)
                        {
                            $manager_email = $manager->email;
                            
                            $data = [
                                'subject' => 'Reservation Update.',
                                'email' => $request->user_email,
                                'status' => $request->reservation_status,
                                'date' => $request->date_of_reservation,
                                'time' => $request->time_of_reservation,
                                'guest_name' => $request->user_name,
                                'reservation_code' => $reservation_code,
                                'Restaurant_name' => $restaurant_name,
                                'no_of_people' => $request->number_of_people,
                                'restaurant_id' => $restaurant->id,
                                'Restaurant_email' => $Restaurant_email,
                                'manager_email' => $manager_email,
                                'Additional_note' => $Additional_note
                            ];
                            
                            Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                                $message->to($data['manager_email'])
                                    ->subject($data['subject']);
                            });
                        }
                        
                        $data = [
                                'subject' => 'Reservation Update.',
                                'email' => $request->user_email,
                                'status' => $request->reservation_status,
                                'date' => $request->date_of_reservation,
                                'time' => $request->time_of_reservation,
                                'guest_name' => $request->user_name,
                                'reservation_code' => $reservation_code,
                                'Restaurant_name' => $restaurant_name,
                                'no_of_people' => $request->number_of_people,
                                'restaurant_id' => $restaurant->id,
                                'Restaurant_email' => $Restaurant_email,
                                'Additional_note' => $Additional_note
                            ];
                    
                    Mail::send('Emails.bookedEmail', $data, function($message) use ($data) {
                        $message->to($data['email'])
                            ->subject($data['subject']);
                    });

                    if (Mail::failures()) {
                        return redirect()->back()
                            ->with('success', 'Reservation Created Successfully. But email not sent');
                    }else{
                        return redirect()->back()
                            ->with('success', 'Reservation Created Successfully. And email sent to user');
                    }

                    }
                    else{
                        $guest_id = $user->id;

                        $info = new Reservation;

                        $info->user_name = $request->user_name;
                        $info->user_email = $request->user_email;
                        $info->user_phone = $request->user_phone;
                        $info->number_of_people = $request->number_of_people;
                        $info->time_of_reservation = $request->time_of_reservation;
                        $info->date_of_reservation = $request->date_of_reservation;
                        $info->reservation_duration = $request->reservation_duration;
                        $info->restaurant_id = $request->restaurant_id;
                        $info->restaurant_name = $restaurant_name;
                        $info->user_id = $guest_id;
                        $info->reservation_status = $request->reservation_status;
                        $info->reservation_tag = $request->reservation_tag;
                        $info->reservation_code = $reservation_code;
                        $info->reservation_type = $request->reservation_type;
                        $info->table_indentifier = $reservation_code;
                        $info->membership_tag = $request->membership_tag;
                        $info->hostess_note = $request->hostess_note;
                        $info->reserver_message = $request->reserver_message;
                        $info->reservation_attachment = $reservation_attachment;
                        $info->created_by = Auth::user()->id;
                        $info->creater_name = Auth::user()->name;
                        //$info->reservation_total_cost = $total_price;

                        $info->save();
                        
                        $Additional_note = '';

                    $restaurant_booking_managers = DB::table('restaurant__users')
                            ->join('users', 'users.id', '=', 'restaurant__users.user_id')
                            ->where('restaurant__users.restaurant_id', $restaurant_id)
                            ->where('restaurant__users.user_role', 'Booking_Manager')
                            ->get();
                            
                        foreach($restaurant_booking_managers as $booking_manager)
                        {
                            $booking_manager_email = $booking_manager->email;
                            
                            $data = [
                                'subject' => 'Reservation Update.',
                                'email' => $request->user_email,
                                'status' => $request->reservation_status,
                                'date' => $request->date_of_reservation,
                                'time' => $request->time_of_reservation,
                                'guest_name' => $request->user_name,
                                'reservation_code' => $reservation_code,
                                'Restaurant_name' => $restaurant_name,
                                'no_of_people' => $request->number_of_people,
                                'restaurant_id' => $restaurant->id,
                                'Restaurant_email' => $Restaurant_email,
                                'booking_manager_email' => $booking_manager_email,
                                'Additional_note' => $Additional_note
                            ];
                            
                            Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                                $message->to($data['booking_manager_email'])
                                    ->subject($data['subject']);
                            });
                        }
                
                        $restaurant_managers = DB::table('restaurant__users')
                            ->join('users', 'users.id', '=', 'restaurant__users.user_id')
                            ->where('restaurant__users.restaurant_id', $restaurant_id)
                            ->Where('restaurant__users.user_role', 'Manager')
                            ->get();

                        foreach($restaurant_managers as $manager)
                        {
                            $manager_email = $manager->email;
                            
                            $data = [
                                'subject' => 'Reservation Update.',
                                'email' => $request->user_email,
                                'status' => $request->reservation_status,
                                'date' => $request->date_of_reservation,
                                'time' => $request->time_of_reservation,
                                'guest_name' => $request->user_name,
                                'reservation_code' => $reservation_code,
                                'Restaurant_name' => $restaurant_name,
                                'no_of_people' => $request->number_of_people,
                                'restaurant_id' => $restaurant->id,
                                'Restaurant_email' => $Restaurant_email,
                                'manager_email' => $manager_email,
                                'Additional_note' => $Additional_note
                            ];
                            
                            Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                                $message->to($data['manager_email'])
                                    ->subject($data['subject']);
                            });
                        }
                        
                        $data = [
                                'subject' => 'Reservation Update.',
                                'email' => $request->user_email,
                                'status' => $request->reservation_status,
                                'date' => $request->date_of_reservation,
                                'time' => $request->time_of_reservation,
                                'guest_name' => $request->user_name,
                                'reservation_code' => $reservation_code,
                                'Restaurant_name' => $restaurant_name,
                                'no_of_people' => $request->number_of_people,
                                'restaurant_id' => $restaurant->id,
                                'Restaurant_email' => $Restaurant_email,
                                'Additional_note' => $Additional_note
                            ];
                    
                    Mail::send('Emails.bookedEmail', $data, function($message) use ($data) {
                        $message->to($data['email'])
                            ->subject($data['subject']);
                    });

                    if (Mail::failures()) {
                        return redirect()->back()
                            ->with('success', 'Reservation Created Successfully. But email not sent');
                    }else{
                        return redirect()->back()
                            ->with('success', 'Reservation Created Successfully. And email sent to user');
                    }

                        /**return redirect()->route('create_reservation', $restaurant_id)
                        ->with('success', 'Reservation Created Successfully.');*/
                    }

                }
                else{
                    return redirect()->back()
                        ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
                }
            }
            else{
                /**perform check on the time and then cancel a reservation if not confirmed
                $reservations = DB::table('reservations')
                    ->where('reservation_status', '=', 'booked')
                    ->where('time_of_reservation', '<',Carbon::parse('-48 hours'))
                    ->get();

                foreach ($reservations as $reservation)
                {
                    if($reservation->reservation_status != "confirmed")
                    {
                        $reservation_id = $reservation->id;

                        DB::table('reservations')
                            ->where('id', $reservation_id)
                            ->update([ 'reservation_status' => 'cancelled']);
                    }
                }*/

                $restaurant_id = $request->restaurant_id;
                $date = date('Y-m-d');
                $all_reservations = DB::table('reservations')
                    ->join('table__to__reservations', 'table__to__reservations.reservation_code', '=', 'reservations.reservation_code')
                    ->join('tables', 'tables.id', '=', 'table__to__reservations.table_id')
                    ->select('reservations.reservation_code', 'reservations.reservation_duration', 'reservations.time_of_reservation')
                    ->where('reservations.restaurant_id', $restaurant_id)
                    ->where('reservations.date_of_reservation', $date)
                    ->get();

                foreach ($all_reservations as $single_reservation){

                    $reservation_duration = $single_reservation->reservation_duration;
                    $reserved_time = Carbon::parse($single_reservation->time_of_reservation)->format('H:i:s');
                    $now = date('H:i:s');
                    $current_time = Carbon::parse($now)->format('H:i:s');
                    $reserved_hours = Carbon::createFromFormat('H:i:s',$reserved_time)->addMinutes($reservation_duration);
                    $reserved_hours = Carbon::parse($reserved_hours)->format('H:i:s');

                    //checking if the total reservation time is over
                    if($reserved_hours < $current_time)
                    {
                        $code = $single_reservation->reservation_code;
                        $reserved_tables = DB::table('table__to__reservations')
                            ->where('reservation_code', $code)->get();
                        foreach ($reserved_tables as $reserved_table)
                        {
                            $tables = DB::table('tables')
                                ->where('id', $reserved_table->table_id)
                                ->update([ 'table_is_booked' => 'no']);
                        }
                    }

                }

                if(Auth::user()->user_type == "Restaurant" || Auth::user()->user_type == "Manager" || Auth::user()->user_type == "Booking_Manager") {
                    $restaurant_id = $request->restaurant_id;
                    $restaurant = DB::table('restaurants')
                        ->where('id', $restaurant_id)
                        ->first();
                    $time = $request->time_of_reservation;
                    $date = $request->date_of_reservation;
                    $today = date('Y-m-d');
                    $now_time = Carbon::now()->format('H:i:s');

                    if($date == $today && $time < $now_time){
                        return redirect()->back()
                            ->with('error', 'You cannot reserve a past time.');
                    }

                    $day_name = Carbon::parse($date)->dayOfWeek;

                    if($restaurant->sunday_open == 'no' && $day_name == 0)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Sundays');
                    }
                    elseif($restaurant->monday_open == 'no' && $day_name == 1)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Mondays');
                    }
                    elseif($restaurant->tuesday_open == 'no' && $day_name == 2)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Tuesdays');
                    }
                    elseif($restaurant->wednesday_open == 'no' && $day_name == 3)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Wednesdays');
                    }
                    elseif($restaurant->thursday_open == 'no' && $day_name == 4)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Thursday');
                    }
                    elseif($restaurant->friday_open == 'no' && $day_name == 5)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Fridays');
                    }
                    elseif($restaurant->saturday_open == 'no' && $day_name == 6)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Saturdays');
                    }

                    $table_ids = $request->table_id;
                    $restaurant_id = $request->restaurant_id;
                    $restaurant_name = $restaurant->Restaurant_name;
                    $reservation_code = Str::random(10);
                    $time = $request->time_of_reservation;
                    $date = $request->date_of_reservation;
                    $no_of_people = $request->number_of_people;
                    $table_max_cover = 0;
                    $table_min_cover = 0;
                    $table_total = 0;
                    $people = intval($request->number_of_people);

                    $table_id_count = count($table_ids);

                    for($i = 0; $i < $table_id_count; $i++)
                    {
                        $table_id = $table_ids[$i];
                        $table = DB::table('tables')->where('id', $table_id)->first();
                        $table_price = intval($table->table_price);
                        $table_total = $table_total + $table_price;

                        $max_cover = $table->max_covers;
                        $min_cover = $table->min_covers;
                        $table_max_cover = $table_max_cover + $max_cover;
                        $table_min_cover = $table_min_cover + $min_cover;

                        $get_table = DB::table('reservations')
                            ->join('table__to__reservations', 'table__to__reservations.reservation_code', '=', 'reservations.reservation_code')
                            ->join('tables', 'tables.id', '=', 'table__to__reservations.table_id')
                            ->where('table__to__reservations.table_id', $table_id)
                            ->where('reservations.date_of_reservation', '=', $date)
                            ->where('reservations.time_of_reservation', '=', $time)
                            ->where('reservations.reservation_status', 'confirmed')
                            ->first();

                        if(!is_null($get_table))
                        {
                            $table_reserved_for = $get_table->time_of_reservation;
                            $new_reservation = $request->time_of_reservation;
                            $table_duration = $get_table->reservation_duration;
                            $total_hour = Carbon::createFromFormat('H:i:s',$table_reserved_for)->addMinutes($table_duration);
                            $total_hour = Carbon::parse($total_hour)->format('H:i:s');

                            if($new_reservation < $total_hour)
                            {
                                return redirect()->back()
                                    ->with('error', 'Reservation not created. You cannot book a table for two different reservations at the same time and date or before the current reservation ends.');
                            }
                        }
                    }

                    $total_price = $table_total * $people;
                    if( $no_of_people > $table_max_cover)
                    {
                        return redirect()->back()->with('error', 'Table capacity is less than the number of people selected.');
                    }
                    elseif ($no_of_people < $table_min_cover)
                    {
                        return redirect()->back()->with('error', 'Table capacity is not utilised properly.');
                    }

                    $user = DB::table('guests')
                        ->where('email', $request->user_email)
                        ->where('restaurant_id', $restaurant_id)
                        ->first();

                    $closing_hour = $restaurant->restaurant_closing_hour;
                    $opening_hour = $restaurant->restaurant_opening_hour;
                    $restaurant_max_capacity = $restaurant->Restaurant_max_capacity;
                    $Restaurant_email = $restaurant->Restaurant_email;

                    /**$booked_seats = DB::table('reservations')
                    ->where('restaurant_id', $restaurant_id)
                    ->where('reservation_status', 'confirmed')
                    ->where('date_of_reservation', 'LIKE', $date)
                    ->where('time_of_reservation', 'LIKE', $time)
                    ->sum('number_of_people');*/

                    $booked_seats = DB::table('reservations')
                        ->join('table__to__reservations', 'table__to__reservations.reservation_code',
                            '=', 'reservations.reservation_code')
                        ->where('reservations.restaurant_id', $restaurant_id)
                        ->where('reservations.reservation_status', 'confirmed')
                        ->where('reservations.date_of_reservation', 'LIKE',$date)
                        ->where('reservations.time_of_reservation', 'LIKE',$time)
                        ->sum('reservations.number_of_people');

                    //$table = DB::table('tables')
                    //       ->where('id', $request->table_id)
                    //     ->first();

                    $table_identifier = $reservation_code;
                    $available_seats = $restaurant_max_capacity - $booked_seats;

                    $check_table = DB::table('reservations')
                        ->join('table__to__reservations', 'table__to__reservations.reservation_code',
                            '=', 'reservations.reservation_code')
                        ->where('table__to__reservations.table_id', $request->table_id)
                        ->where('reservations.date_of_reservation','LIKE', $date)
                        ->where('reservations.time_of_reservation','LIKE', $time)
                        ->get();

                    $count_check_table = count($check_table);

                    if($time < $opening_hour || $time > $closing_hour)
                    {
                        return redirect()->back()->with('error', 'Restaurant is Closed at the specified hour.');
                    }
                    elseif ($no_of_people > $available_seats)
                    {
                        return redirect()->back()->with('error', 'Number of People Exceeds the Capacity of Your Restaurant.');
                    }
                    elseif ($no_of_people > $restaurant_max_capacity)
                    {
                        return redirect()->back()->with('error', 'Number of People Exceeds the Capacity of Your Restaurant.');
                    }
                    elseif($count_check_table > 0)
                    {
                        return redirect()->back()->with('error', "'Table is booked at ' + $time + 'try other available times.'");
                    }
                    else{
                        for($i = 0; $i < $table_id_count; $i++)
                        {
                            $table_id = $table_ids[$i];
                            $table_to_reservation = new Table_To_Reservation;
                            $table_to_reservation->reservation_code = $reservation_code;
                            $table_to_reservation->table_id = $table_id;
                            $table_to_reservation->save();
                            DB::table('tables')
                                ->where('id', $table_id)
                                ->update([
                                    'table_is_booked'=>'yes'
                                ]);
                        }
                    }
                    if (is_null($user)) {
                        $data = new Guest;

                        $data->user_name = $request->user_name;
                        $data->email = $request->user_email;
                        $data->phone_number = $request->user_phone;
                        $data->restaurant_id = $restaurant_id;
                        $date->membership_tag = $request->membership_tag;
                        $data->save();

                        $guest_id = $data->id;

                        $info = new Reservation;

                        $info->user_name = $request->user_name;
                        $info->user_email = $request->user_email;
                        $info->user_phone = $request->user_phone;
                        $info->number_of_people = $request->number_of_people;
                        $info->time_of_reservation = $request->time_of_reservation;
                        $info->date_of_reservation = $request->date_of_reservation;
                        $info->reservation_duration = $request->reservation_duration;
                        $info->restaurant_id = $request->restaurant_id;
                        $info->restaurant_name = $restaurant_name;
                        $info->user_id = $guest_id;
                        $info->reservation_status = $request->reservation_status;
                        $info->reservation_tag = $request->reservation_tag;
                        $info->reservation_code = $reservation_code;
                        $info->reservation_type = $request->reservation_type;
                        $info->table_indentifier = $reservation_code;
                        $info->reservation_total_cost = $total_price;
                        $info->hostess_note = $request->hostess_note;
                        $info->reserver_message = $request->reserver_message;
                        $info->reservation_attachment = $reservation_attachment;
                        $info->created_by = Auth::user()->id;
                        $info->creater_name = Auth::user()->name;

                        $info->save();
                        
                        $Additional_note = '';

                    $restaurant_booking_managers = DB::table('restaurant__users')
                            ->join('users', 'users.id', '=', 'restaurant__users.user_id')
                            ->where('restaurant__users.restaurant_id', $restaurant_id)
                            ->where('restaurant__users.user_role', 'Booking_Manager')
                            ->get();
                            
                        foreach($restaurant_booking_managers as $booking_manager)
                        {
                            $booking_manager_email = $booking_manager->email;
                            
                            $data = [
                                'subject' => 'Reservation Update.',
                                'email' => $request->user_email,
                                'status' => $request->reservation_status,
                                'date' => $request->date_of_reservation,
                                'time' => $request->time_of_reservation,
                                'guest_name' => $request->user_name,
                                'reservation_code' => $reservation_code,
                                'Restaurant_name' => $restaurant_name,
                                'no_of_people' => $request->number_of_people,
                                'restaurant_id' => $restaurant->id,
                                'Restaurant_email' => $Restaurant_email,
                                'booking_manager_email' => $booking_manager_email,
                                'Additional_note' => $Additional_note
                            ];
                            
                            Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                                $message->to($data['booking_manager_email'])
                                    ->subject($data['subject']);
                            });
                            
                        }
                
                        $restaurant_managers = DB::table('restaurant__users')
                            ->join('users', 'users.id', '=', 'restaurant__users.user_id')
                            ->where('restaurant__users.restaurant_id', $restaurant_id)
                            ->Where('restaurant__users.user_role', 'Manager')
                            ->get();

                        foreach($restaurant_managers as $manager)
                        {
                            $manager_email = $manager->email;
                            
                            $data = [
                                'subject' => 'Reservation Update.',
                                'email' => $request->user_email,
                                'status' => $request->reservation_status,
                                'date' => $request->date_of_reservation,
                                'time' => $request->time_of_reservation,
                                'guest_name' => $request->user_name,
                                'reservation_code' => $reservation_code,
                                'Restaurant_name' => $restaurant_name,
                                'no_of_people' => $request->number_of_people,
                                'restaurant_id' => $restaurant->id,
                                'Restaurant_email' => $Restaurant_email,
                                'manager_email' => $manager_email,
                                'Additional_note' => $Additional_note
                            ];
                            
                            Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                                $message->to($data['manager_email'])
                                    ->subject($data['subject']);
                            });
                            
                        }
                        
                        $data = [
                        'subject' => 'Reservation Update.',
                        'email' => $request->user_email,
                        'status' => $request->reservation_status,
                        'date' => $request->date_of_reservation,
                        'time' => $request->time_of_reservation,
                        'guest_name' => $request->user_name,
                        'reservation_code' => $reservation_code,
                        'Restaurant_name' => $restaurant_name,
                        'no_of_people' => $request->number_of_people,
                        'restaurant_id' => $restaurant->id,
                        'Restaurant_email' => $Restaurant_email,
                        'Additional_note' => $Additional_note
                    ];
                    
                    Mail::send('Emails.bookedEmail', $data, function($message) use ($data) {
                        $message->to($data['email'])
                            ->subject($data['subject']);
                    });

                    if (Mail::failures()) {
                        return redirect()->back()
                            ->with('success', 'Reservation Created Successfully. But email not sent');
                    }else{
                        return redirect()->route()
                            ->with('success', 'Reservation Created Successfully. And email sent to user');
                    }

                    }
                    else{
                        $guest_id = $user->id;

                        $info = new Reservation;

                        $info->user_name = $request->user_name;
                        $info->user_email = $request->user_email;
                        $info->user_phone = $request->user_phone;
                        $info->number_of_people = $request->number_of_people;
                        $info->time_of_reservation = $request->time_of_reservation;
                        $info->date_of_reservation = $request->date_of_reservation;
                        $info->reservation_duration = $request->reservation_duration;
                        $info->restaurant_id = $request->restaurant_id;
                        $info->restaurant_name = $restaurant_name;
                        $info->user_id = $guest_id;
                        $info->reservation_status = $request->reservation_status;
                        $info->reservation_tag = $request->reservation_tag;
                        $info->reservation_code = $reservation_code;
                        $info->reservation_type = $request->reservation_type;
                        $info->table_indentifier = $reservation_code;
                        $info->reservation_total_cost = $total_price;
                        $info->hostess_note = $request->hostess_note;
                        $info->reserver_message = $request->reserver_message;
                        $info->reservation_attachment = $reservation_attachment;
                        $info->created_by = Auth::user()->id;
                        $info->creater_name = Auth::user()->name;

                        $info->save();
                        
                        $Additional_note = '';

                        $restaurant_booking_managers = DB::table('restaurant__users')
                            ->join('users', 'users.id', '=', 'restaurant__users.user_id')
                            ->where('restaurant__users.restaurant_id', $restaurant_id)
                            ->where('restaurant__users.user_role', 'Booking_Manager')
                            ->get();
                            
                        foreach($restaurant_booking_managers as $booking_manager)
                        {
                            $booking_manager_email = $booking_manager->email;
                            
                            $data = [
                                'subject' => 'Reservation Update.',
                                'email' => $request->user_email,
                                'status' => $request->reservation_status,
                                'date' => $request->date_of_reservation,
                                'time' => $request->time_of_reservation,
                                'guest_name' => $request->user_name,
                                'reservation_code' => $reservation_code,
                                'Restaurant_name' => $restaurant_name,
                                'no_of_people' => $request->number_of_people,
                                'restaurant_id' => $restaurant->id,
                                'Restaurant_email' => $Restaurant_email,
                                'booking_manager_email' => $booking_manager_email,
                                'Additional_note' => $Additional_note
                            ];
                            
                            Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                                $message->to($data['booking_manager_email'])
                                    ->subject($data['subject']);
                            });
                            
                        }
                
                        $restaurant_managers = DB::table('restaurant__users')
                            ->join('users', 'users.id', '=', 'restaurant__users.user_id')
                            ->where('restaurant__users.restaurant_id', $restaurant_id)
                            ->Where('restaurant__users.user_role', 'Manager')
                            ->get();

                        foreach($restaurant_managers as $manager)
                        {
                            $manager_email = $manager->email;
                            
                            $data = [
                                'subject' => 'Reservation Update.',
                                'email' => $request->user_email,
                                'status' => $request->reservation_status,
                                'date' => $request->date_of_reservation,
                                'time' => $request->time_of_reservation,
                                'guest_name' => $request->user_name,
                                'reservation_code' => $reservation_code,
                                'Restaurant_name' => $restaurant_name,
                                'no_of_people' => $request->number_of_people,
                                'restaurant_id' => $restaurant->id,
                                'Restaurant_email' => $Restaurant_email,
                                'manager_email' => $manager_email,
                                'Additional_note' => $Additional_note
                            ];
                            
                            Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                                $message->to($data['manager_email'])
                                    ->subject($data['subject']);
                            });
                            
                        }
                        
                        $data = [
                        'subject' => 'Reservation Update.',
                        'email' => $request->user_email,
                        'status' => $request->reservation_status,
                        'date' => $request->date_of_reservation,
                        'time' => $request->time_of_reservation,
                        'guest_name' => $request->user_name,
                        'reservation_code' => $reservation_code,
                        'Restaurant_name' => $restaurant_name,
                        'no_of_people' => $request->number_of_people,
                        'restaurant_id' => $restaurant->id,
                        'Restaurant_email' => $Restaurant_email,
                        'Additional_note' => $Additional_note
                    ];
                    
                    Mail::send('Emails.bookedEmail', $data, function($message) use ($data) {
                        $message->to($data['email'])
                            ->subject($data['subject']);
                    });

                    if (Mail::failures()) {
                        return redirect()->back()
                            ->with('success', 'Reservation Created Successfully. But email not sent');
                    }else{
                        return redirect()->back()
                            ->with('success', 'Reservation Created Successfully. And email sent to user');
                    }

                        /**return redirect()->route('create_reservation', $restaurant_id)
                        ->with('success', 'Reservation Created Successfully.');*/
                    }

                }
                else{
                    return redirect()->back()
                        ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
                }
            }

        }
        
        else{
            return redirect()->route('login');
        }

    }

    public function booking_history($id){

        if(Auth::check())
        {
            $user = DB::table('users')
                ->where('id', $id)
                ->first();
                
            $user_email = $user->email;
            
            $reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->select('reservations.reservation_code', 'reservations.date_of_reservation', 'reservations.time_of_reservation',
                    'reservations.number_of_people', 'reservations.reservation_status', 'reservations.reservation_type',
                    'reservations.reservation_tag', 'reservations.reservation_duration', 'reservations.reservation_total_cost',
                    'reservations.user_name' ,'restaurants.Restaurant_name')
                ->where('reservations.user_email', $user_email)
                ->get();

            return view('Reservation.booking_history', compact('reservations'))
                ->with('i', (request()->input('page', 1) - 1) * 5);

            //var_dump($reservations);
            //die();
        }
        else{
            return redirect()->route('login');
        }
    }

    public function sort_by_date(Request $request)
    {
        if(Auth::check())
        {
            /**perform check on the time and then cancel a reservation if not confirmed
                $reservations = DB::table('reservations')
                    ->where('reservation_status', '=', 'booked')
                    ->where('time_of_reservation', '<',Carbon::parse('-48 hours'))
                    ->get();

                foreach ($reservations as $reservation)
                {
                    if($reservation->reservation_status != "confirmed")
                    {
                        $reservation_id = $reservation->id;

                        DB::table('reservations')
                            ->where('id', $reservation_id)
                            ->update([ 'reservation_status' => 'cancelled']);
                    }
                }*/
                
            $request->validate([
                'restaurant_id' => 'required|int',
                'date_of_reservation' => 'required|date'
            ]);

            //checking the time and free a table if reservation time is over
            $restaurant_id = $request->restaurant_id;
            $date = date('Y-m-d');
            $all_reservations = DB::table('reservations')
                ->join('table__to__reservations', 'table__to__reservations.reservation_code', '=', 'reservations.reservation_code')
                ->join('tables', 'tables.id', '=', 'table__to__reservations.table_id')
                ->select('reservations.reservation_code', 'reservations.reservation_duration', 'reservations.time_of_reservation')
                ->where('reservations.restaurant_id', $restaurant_id)
                ->where('reservations.date_of_reservation', $date)
                ->get();

            foreach ($all_reservations as $single_reservation){

                $reservation_duration = $single_reservation->reservation_duration;
                $reserved_time = Carbon::parse($single_reservation->time_of_reservation)->format('H:i:s');
                $now = date('H:i:s');
                $current_time = Carbon::parse($now)->format('H:i:s');
                $reserved_hours = Carbon::createFromFormat('H:i:s',$reserved_time)->addMinutes($reservation_duration);
                $reserved_hours = Carbon::parse($reserved_hours)->format('H:i:s');

                //checking if the total reservation time is over
                if($reserved_hours < $current_time)
                {
                    $code = $single_reservation->reservation_code;
                    $reserved_tables = DB::table('table__to__reservations')
                        ->where('reservation_code', $code)->get();
                    foreach ($reserved_tables as $reserved_table)
                    {
                        $tables = DB::table('tables')
                            ->where('id', $reserved_table->table_id)
                            ->update([ 'table_is_booked' => 'no']);
                    }
                }

            }
            //checking process ends here
            
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

            $restaurant_id = $request->restaurant_id;
            $date = $request->date_of_reservation;

            $restaurant = DB::table('restaurants')
                ->where('id', '=', $restaurant_id)
                ->first();
                
            $rest_type = $restaurant->Reservation_update_type;

            $free_tables = DB::table('tables')
                ->where('restaurant_id', $restaurant_id)
                ->where('table_status', '=', 'active')
                ->get();

            $tables = Table::with('Reservation')
                ->where('table_status', '=', 'active')
                ->where('restaurant_id', $restaurant_id)
                ->get();

            $today = today();
            $today = date_format(date_create($today), 'Y-m-d');

            $opening_hour = $restaurant->restaurant_opening_hour;
            $closing_hour = $restaurant->restaurant_closing_hour;
            $duration = $restaurant->Restaurant_duration;
            
            $available_hours_breakfast = [];

            $available_hours_dinner = [];

            $breakfast_hours = DB::table('restaurant_hours')
                ->where('restaurant_id', $restaurant->id)
                ->where('hour_name', '=', 'Breakfast_Hours')
                ->first();

            $lunch_hours = DB::table('restaurant_hours')
                ->where('restaurant_id', $restaurant->id)
                ->where('hour_name', '=', 'Lunch_Hours')
                ->first();

            $dinner_hours = DB::table('restaurant_hours')
                ->where('restaurant_id', $restaurant->id)
                ->where('hour_name', '=', 'Dinner_Hours')
                ->first();

            if(!is_null($breakfast_hours) || !is_null($lunch_hours) || !is_null($dinner_hours))
            {
                $free_hours = array();

                if(!is_null($breakfast_hours))
                {
                    $hourdiff = round((strtotime($breakfast_hours->end_hour) - strtotime($breakfast_hours->start_hour))/3600, 1);
                    $open_hour = Carbon::parse($breakfast_hours->start_hour)->format('H:i:s');

                    $max = $hourdiff * 2;
                    for($i=0; $i < $max; $i++)
                    {
                        $opening_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                        //$opening_hour = date('h:i A', strtotime($opening_hour));
                        $open_hour = Carbon::parse($opening_hour)->format('H:i:s');
                        $hour_name = $breakfast_hours->hour_name;

                        //$open_hour = $opening_hour;
                        $available_hours_breakfast['hour'] = $open_hour;
                        $available_hours_breakfast['hour_name'] = 'Breakfast Hour';

                        $available_hours[] = $available_hours_breakfast;
                    }
                }

                $available_hours_lunch = [];

                if(!is_null($lunch_hours))
                {
                    $hourdiff = round((strtotime($lunch_hours->end_hour) - strtotime($lunch_hours->start_hour))/3600, 1);
                    $open_hour = Carbon::parse($lunch_hours->start_hour)->format('H:i:s');

                    $max = $hourdiff * 2;
                    for($i=0; $i < $max; $i++)
                    {
                        $opening_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                        //$opening_hour = date('h:i A', strtotime($opening_hour));
                        $open_hour = Carbon::parse($opening_hour)->format('H:i:s');
                        $hour_name = $lunch_hours->hour_name;

                        //$open_hour = $opening_hour;
                        $available_hours_lunch['hour'] = $open_hour;
                        $available_hours_lunch['hour_name'] = 'Lunch Hour';

                        $available_hours[] = $available_hours_lunch;
                    }
                }

                if(!is_null($dinner_hours))
                {
                    $hourdiff = round((strtotime($dinner_hours->end_hour) - strtotime($dinner_hours->start_hour))/3600, 1);
                    $open_hour = Carbon::parse($dinner_hours->start_hour)->format('H:i:s');

                    $max = $hourdiff * 2;
                    for($i=0; $i < $max; $i++)
                    {
                        $opening_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                        //$opening_hour = date('h:i A', strtotime($opening_hour));
                        $open_hour = Carbon::parse($opening_hour)->format('H:i:s');
                        $hour_name = $dinner_hours->hour_name;

                        //$open_hour = $opening_hour;

                        $available_hours_dinner['hour'] = $open_hour;
                        $available_hours_dinner['hour_name'] = 'Dinner Hour';

                        $available_hours[] = $available_hours_dinner;
                    }
                }

                //$collection = collect($available_hours_breakfast);
                //$merged = $collection->merge();
                //$merged = $collection->merge($available_hours_lunch);
                //$available_hours = $merged->merge($available_hours_dinner);
                //$available_hours->all();

                $hour_count = count($available_hours);

            }

            elseif(is_null($breakfast_hours) && is_null($lunch_hours) && is_null($dinner_hours))
            {
                $opening_hour = $restaurant->restaurant_opening_hour;
                $closing_hour = $restaurant->restaurant_closing_hour;
                $hourdiff = round((strtotime($closing_hour) - strtotime($opening_hour))/3600, 1);
                $open_hour = Carbon::parse($opening_hour)->format('H:i:s');

                $free_hours = array();
                $available_hours = array();
                $hour_count = count($available_hours);

                $max = $hourdiff * 2;
                for($i=0; $i < $max; $i++)
                {
                    $opening_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                    //$opening_hour = date('h:i A', strtotime($opening_hour));
                    $open_hour = Carbon::parse($opening_hour)->format('H:i:s');

                    //$open_hour = $opening_hour;
                    $free_hours[] = $open_hour;
                }
            }
            
            $number_of_tables = count($tables);

            $available_tables = DB::table('tables')
                ->where('table_is_booked', 'no')
                ->where('restaurant_id', $restaurant_id)
                ->get();

            $available_tables = count($available_tables);
            
            /**$available_seats = DB::table('tables')
                ->where('table_is_booked', 'no')
                ->where('restaurant_id', $restaurant_id)
                ->sum('max_covers');*/
            $total_seats = DB::table('tables')
                ->where('table_is_booked', 'no')
                ->where('restaurant_id', $restaurant_id)
                ->sum('max_covers');

            $occupied_seats = DB::table('reservations')
                ->join('table__to__reservations', 'table__to__reservations.reservation_code', '=', 'reservations.reservation_code')
                ->join('tables', 'tables.id', '=', 'table__to__reservations.table_id')
                ->where('reservations.restaurant_id', $restaurant_id)
                ->where('reservations.date_of_reservation', '=', $date)
                ->sum('tables.max_covers');

            $available_s = $total_seats - $occupied_seats;

            $tables_reserved = DB::table('tables')
                ->where('table_is_booked', 'yes')
                ->where('restaurant_id', $restaurant_id)
                ->get();
                
            if($available_s > 0)
            {
                $available_seats = $available_s;
            }
            else{
                $available_seats = '0';
            }

            $tables_reserved = count($tables_reserved);

            $reservations = DB::table( 'reservations')
                ->where('reservations.date_of_reservation', $date)
                ->where('reservations.restaurant_id', $restaurant_id)
                ->get();
                
            $booked = DB::table('reservations')
                ->where('date_of_reservation', $date)
                ->where('restaurant_id', $restaurant_id)
                ->where('reservation_status', 'booked')
                ->get();
            $booked = count($booked);

            $confirmed = DB::table('reservations')
                ->where('date_of_reservation', $date)
                ->where('restaurant_id', $restaurant_id)
                ->where('reservation_status', 'confirmed')
                ->get();
            $confirmed = count($confirmed);
            
            $completed = DB::table('reservations')
                ->where('date_of_reservation', $date)
                ->where('restaurant_id', $restaurant_id)
                ->where('reservation_status', 'Completed')
                ->get();
            $completed = count($completed);

            $cancelled = DB::table('reservations')
                ->where('date_of_reservation', $date)
                ->where('restaurant_id', $restaurant_id)
                ->where('reservation_status', 'cancelled')
                ->get();
            $cancelled = count($cancelled);
            
            $late = DB::table('reservations')
                ->where('date_of_reservation', $date)
                ->where('restaurant_id', $restaurant_id)
                ->where('reservation_status', 'late')
                ->get();
            $late = count($late);
            
            $wait_listed = DB::table('reservations')
                ->where('date_of_reservation', $date)
                ->where('restaurant_id', $restaurant_id)
                ->where('reservation_status', 'wait_list')
                ->get();
            $wait_listed = count($wait_listed);
                
            $number_of_reservations = count($reservations);
            
            $memberships = DB::table('restaurant_memberships')
                ->where('restaurant_id', $restaurant_id)
                ->get();
                
            $open_tables = DB::table('tables')
                ->where('table_is_booked', 'no')
                ->where('restaurant_id', $restaurant_id)
                ->where('table_status', '=', 'active')
                ->get();
                
            $open_tables = count($open_tables);
                
            $guest_types = DB::table('guest_types')
                    ->where('restaurant_id', $restaurant_id)
                    ->get();

            return view('Admin.Reservation.sort_by_date',
                compact('tables', 'restaurants', 'restaurant', 'reservations', 'restaurant_id', 'free_tables', 'date', 'available_hours', 
                'today', 'number_of_tables', 'available_tables', 'available_seats', 'open_tables', 'tables_reserved', 'booked', 'late',  'number_of_reservations', 
                'confirmed', 'cancelled', 'memberships', 'rest_type', 'guest_types', 'completed', 'hour_count', 'free_hours', 'wait_listed'))
                ->with('i');

        }
        else{
            return redirect()->route('login');
        }
    }

    public function create_reservation($id)
    {
        if(Auth::check())
        {
            /**perform check on the time and then cancel a reservation if not confirmed
                $reservations = DB::table('reservations')
                    ->where('reservation_status', '=', 'booked')
                    ->where('time_of_reservation', '<',Carbon::parse('-48 hours'))
                    ->get();

                foreach ($reservations as $reservation)
                {
                    if($reservation->reservation_status != "confirmed")
                    {
                        $reservation_id = $reservation->id;

                        DB::table('reservations')
                            ->where('id', $reservation_id)
                            ->update([ 'reservation_status' => 'cancelled']);
                    }
                }*/
                
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

            $restaurant = DB::table('restaurants')
                ->where('id', $id)
                ->first();

            $rest_type = $restaurant->Reservation_update_type;

            if ($rest_type == 'Manual')
            {
                
                $restaurant_id = $id;

                $date = today();
                $date = date_format(date_create($date), 'Y-m-d');

                $restaurant = DB::table('restaurants')
                    ->where('id', $id)
                    ->first();

                $free_tables = DB::table('tables')
                    ->where('restaurant_id', $restaurant_id)
                    ->where('table_status', '=', 'active')
                    ->get();

                $tables = DB::table('tables')
                    ->where('restaurant_id', $restaurant_id)
                    ->where('table_status', '=', 'active')
                    ->get();

                //$tables = DB::table('table__to__reservations')
                //  ->join('tables', 'table__to__reservations.table_id', '=', 'tables.id')
                // ->join('reservations', 'table__to__reservations.reservation_code', '=', 'reservations.reservation_code')
                // ->get();

                $opening_hour = $restaurant->restaurant_opening_hour;
                $closing_hour = $restaurant->restaurant_closing_hour;
                $duration = $restaurant->Restaurant_duration;
                $duration = $restaurant->Restaurant_duration;

                $available_hours_breakfast = [];

                $available_hours_dinner = [];

                $breakfast_hours = DB::table('restaurant_hours')
                    ->where('restaurant_id', $restaurant->id)
                    ->where('hour_name', '=', 'Breakfast_Hours')
                    ->first();

                $lunch_hours = DB::table('restaurant_hours')
                    ->where('restaurant_id', $restaurant->id)
                    ->where('hour_name', '=', 'Lunch_Hours')
                    ->first();

                $dinner_hours = DB::table('restaurant_hours')
                    ->where('restaurant_id', $restaurant->id)
                    ->where('hour_name', '=', 'Dinner_Hours')
                    ->first();

                if(!is_null($breakfast_hours) || !is_null($lunch_hours) || !is_null($dinner_hours))
                {
                    $free_hours = array();

                    if(!is_null($breakfast_hours))
                    {
                        $hourdiff = round((strtotime($breakfast_hours->end_hour) - strtotime($breakfast_hours->start_hour))/3600, 1);
                        $open_hour = Carbon::parse($breakfast_hours->start_hour)->format('H:i:s');

                        $max = $hourdiff * 2;
                        for($i=0; $i < $max; $i++)
                        {
                            $opening_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                            //$opening_hour = date('h:i A', strtotime($opening_hour));
                            $open_hour = Carbon::parse($opening_hour)->format('H:i:s');
                            $hour_name = $breakfast_hours->hour_name;

                            //$open_hour = $opening_hour;
                            $available_hours_breakfast['hour'] = $open_hour;
                            $available_hours_breakfast['hour_name'] = 'Breakfast Hour';

                            $available_hours[] = $available_hours_breakfast;
                        }
                    }

                    $available_hours_lunch = [];

                    if(!is_null($lunch_hours))
                    {
                        $hourdiff = round((strtotime($lunch_hours->end_hour) - strtotime($lunch_hours->start_hour))/3600, 1);
                        $open_hour = Carbon::parse($lunch_hours->start_hour)->format('H:i:s');

                        $max = $hourdiff * 2;
                        for($i=0; $i < $max; $i++)
                        {
                            $opening_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                            //$opening_hour = date('h:i A', strtotime($opening_hour));
                            $open_hour = Carbon::parse($opening_hour)->format('H:i:s');
                            $hour_name = $lunch_hours->hour_name;

                            //$open_hour = $opening_hour;
                            $available_hours_lunch['hour'] = $open_hour;
                            $available_hours_lunch['hour_name'] = 'Lunch Hour';

                            $available_hours[] = $available_hours_lunch;
                        }
                    }

                    if(!is_null($dinner_hours))
                    {
                        $hourdiff = round((strtotime($dinner_hours->end_hour) - strtotime($dinner_hours->start_hour))/3600, 1);
                        $open_hour = Carbon::parse($dinner_hours->start_hour)->format('H:i:s');

                        $max = $hourdiff * 2;
                        for($i=0; $i < $max; $i++)
                        {
                            $opening_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                            //$opening_hour = date('h:i A', strtotime($opening_hour));
                            $open_hour = Carbon::parse($opening_hour)->format('H:i:s');
                            $hour_name = $dinner_hours->hour_name;

                            //$open_hour = $opening_hour;

                            $available_hours_dinner['hour'] = $open_hour;
                            $available_hours_dinner['hour_name'] = 'Dinner Hour';

                            $available_hours[] = $available_hours_dinner;
                        }
                    }

                    $hour_count = count($available_hours);

                }

                elseif(is_null($breakfast_hours) && is_null($lunch_hours) && is_null($dinner_hours))
                {
                    $opening_hour = $restaurant->restaurant_opening_hour;
                    $closing_hour = $restaurant->restaurant_closing_hour;
                    $hourdiff = round((strtotime($closing_hour) - strtotime($opening_hour))/3600, 1);
                    $open_hour = Carbon::parse($opening_hour)->format('H:i:s');

                    $free_hours = array();
                    $available_hours = array();
                    $hour_count = count($available_hours);

                    $max = $hourdiff * 2;
                    for($i=0; $i < $max; $i++)
                    {
                        $opening_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                        //$opening_hour = date('h:i A', strtotime($opening_hour));
                        $open_hour = Carbon::parse($opening_hour)->format('H:i:s');

                        //$open_hour = $opening_hour;
                        $free_hours[] = $open_hour;
                    }
                }

                $number_of_tables = count($tables);
                
                /**$available_tables = DB::table('reservations')
                            ->join('table__to__reservations', 'table__to__reservations.reservation_code', '=', 'reservations.reservation_code')
                            ->join('tables', 'tables.id', '=', 'table__to__reservations.table_id')
                            ->where('tables.table_is_booked', 'no')
                            ->where('reservations.date_of_reservation', '=', $date)
                            ->where('tables.restaurant_id', $restaurant_id)
                            ->get();*/

                $available_tables = DB::table('tables')
                    ->where('table_is_booked', 'no')
                    ->where('restaurant_id', $restaurant_id)
                    ->get();

                $available_tables = count($available_tables);
                //var_dump($available_tables);
                //die();
                /**$available_seats = DB::table('tables')
                    ->where('table_is_booked', 'no')
                    ->where('restaurant_id', $restaurant_id)
                    ->sum('max_covers');*/
                $total_seats = DB::table('tables')
                    ->where('table_is_booked', 'no')
                    ->where('restaurant_id', $restaurant_id)
                    ->sum('max_covers');

                $occupied_seats = DB::table('reservations')
                    ->join('table__to__reservations', 'table__to__reservations.reservation_code', '=', 'reservations.reservation_code')
                    ->join('tables', 'tables.id', '=', 'table__to__reservations.table_id')
                    ->where('reservations.restaurant_id', $restaurant_id)
                    ->where('reservations.date_of_reservation', '=', $date)
                    ->sum('tables.max_covers');

                $available_s = $total_seats - $occupied_seats;
                
                if($available_s > 0)
                {
                    $available_seats = $available_s;
                }
                else{
                    $available_seats = '0';
                }

                $tables_reserved = DB::table('tables')
                    ->where('table_is_booked', 'yes')
                    ->where('restaurant_id', $restaurant_id)
                    ->get();

                $tables_reserved = count($tables_reserved);

                $reservations = DB::table('reservations')
                    ->where('date_of_reservation', $date)
                    ->where('restaurant_id', $restaurant_id)
                    ->get();

                $booked = DB::table('reservations')
                    ->where('date_of_reservation', $date)
                    ->where('restaurant_id', $restaurant_id)
                    ->where('reservation_status', 'booked')
                    ->get();
                $booked = count($booked);

                $confirmed = DB::table('reservations')
                    ->where('date_of_reservation', $date)
                    ->where('restaurant_id', $restaurant_id)
                    ->where('reservation_status', 'confirmed')
                    ->get();
                $confirmed = count($confirmed);

                $cancelled = DB::table('reservations')
                    ->where('date_of_reservation', $date)
                    ->where('restaurant_id', $restaurant_id)
                    ->where('reservation_status', 'cancelled')
                    ->get();
                $cancelled = count($cancelled);
                
                $completed = DB::table('reservations')
                    ->where('date_of_reservation', $date)
                    ->where('restaurant_id', $restaurant_id)
                    ->where('reservation_status', 'Completed')
                    ->get();
                $completed = count($completed);
                
                $late = DB::table('reservations')
                    ->where('date_of_reservation', $date)
                    ->where('restaurant_id', $restaurant_id)
                    ->where('reservation_status', 'late')
                    ->get();
                $late = count($late);
                
                $wait_listed = DB::table('reservations')
                    ->where('date_of_reservation', $date)
                    ->where('restaurant_id', $restaurant_id)
                    ->where('reservation_status', 'wait_list')
                    ->get();
                $wait_listed = count($wait_listed);

                $number_of_reservations = count($reservations);

                $memberships = DB::table('restaurant_memberships')
                    ->where('restaurant_id', $restaurant_id)
                    ->get();
                    
                $guest_types = DB::table('guest_types')
                    ->where('restaurant_id', $restaurant_id)
                    ->get();
                    
                $open_tables = DB::table('tables')
                ->where('table_is_booked', 'no')
                ->where('restaurant_id', $restaurant_id)
                ->where('table_status', '=', 'active')
                ->get();
                
                $open_tables = count($open_tables);

                return view('Admin.Reservation.create',
                    compact('tables', 'restaurants', 'restaurant', 'reservations', 'restaurant_id',
                        'free_tables', 'date', 'available_hours', 'open_tables', 'number_of_tables', 'available_tables', 'available_seats',
                        'tables_reserved', 'number_of_reservations', 'memberships', 'rest_type', 'booked', 'confirmed',
                    'cancelled', 'late', 'guest_types', 'completed', 'hour_count', 'free_hours', 'wait_listed'))
                    ->with('i');
            }
            else{
                $restaurant_id = $id;

                //checking the time and free table if reservation time is over
                $date = date('Y-m-d');
                $all_reservations = DB::table('reservations')
                    ->join('table__to__reservations', 'table__to__reservations.reservation_code', '=', 'reservations.reservation_code')
                    ->join('tables', 'tables.id', '=', 'table__to__reservations.table_id')
                    ->select('reservations.reservation_code', 'reservations.reservation_duration', 'reservations.time_of_reservation')
                    ->where('reservations.restaurant_id', $restaurant_id)
                    ->where('reservations.date_of_reservation', $date)
                    ->get();

                foreach ($all_reservations as $single_reservation){

                    $reservation_duration = $single_reservation->reservation_duration;
                    $reserved_time = Carbon::parse($single_reservation->time_of_reservation)->format('H:i:s');
                    $now = date('H:i:s');
                    $current_time = Carbon::parse($now)->format('H:i:s');
                    $reserved_hours = Carbon::createFromFormat('H:i:s',$reserved_time)->addMinutes($reservation_duration);
                    $reserved_hours = Carbon::parse($reserved_hours)->format('H:i:s');

                    //checking if the total reservation time is over
                    if($reserved_hours < $current_time)
                    {
                        $code = $single_reservation->reservation_code;
                        $reserved_tables = DB::table('table__to__reservations')
                            ->where('reservation_code', $code)->get();
                        foreach ($reserved_tables as $reserved_table)
                        {
                            $tables = DB::table('tables')
                                ->where('id', $reserved_table->table_id)
                                ->update([ 'table_is_booked' => 'no']);
                        }
                    }

                }

                $date = today();
                $date = date_format(date_create($date), 'Y-m-d');

                $restaurant = DB::table('restaurants')
                    ->where('id', $id)
                    ->first();

                $free_tables = DB::table('tables')
                    ->where('restaurant_id', $restaurant_id)
                    ->where('table_status', '=', 'active')
                    ->get();

                $tables = DB::table('tables')
                    ->where('restaurant_id', $restaurant_id)
                    ->where('table_status', '=', 'active')
                    ->get();

                //$tables = DB::table('table__to__reservations')
                //  ->join('tables', 'table__to__reservations.table_id', '=', 'tables.id')
                // ->join('reservations', 'table__to__reservations.reservation_code', '=', 'reservations.reservation_code')
                // ->get();

                $opening_hour = $restaurant->restaurant_opening_hour;
                $closing_hour = $restaurant->restaurant_closing_hour;
                $duration = $restaurant->Restaurant_duration;
                $available_hours_breakfast = [];

                $available_hours_dinner = [];

                $breakfast_hours = DB::table('restaurant_hours')
                    ->where('restaurant_id', $restaurant->id)
                    ->where('hour_name', '=', 'Breakfast_Hours')
                    ->first();

                $lunch_hours = DB::table('restaurant_hours')
                    ->where('restaurant_id', $restaurant->id)
                    ->where('hour_name', '=', 'Lunch_Hours')
                    ->first();

                $dinner_hours = DB::table('restaurant_hours')
                    ->where('restaurant_id', $restaurant->id)
                    ->where('hour_name', '=', 'Dinner_Hours')
                    ->first();

                if(!is_null($breakfast_hours) || !is_null($lunch_hours) || !is_null($dinner_hours))
                {
                    $free_hours = array();

                    if(!is_null($breakfast_hours))
                    {
                        $hourdiff = round((strtotime($breakfast_hours->end_hour) - strtotime($breakfast_hours->start_hour))/3600, 1);
                        $open_hour = Carbon::parse($breakfast_hours->start_hour)->format('H:i:s');

                        $max = $hourdiff * 2;
                        for($i=0; $i < $max; $i++)
                        {
                            $opening_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                            //$opening_hour = date('h:i A', strtotime($opening_hour));
                            $open_hour = Carbon::parse($opening_hour)->format('H:i:s');
                            $hour_name = $breakfast_hours->hour_name;

                            //$open_hour = $opening_hour;
                            $available_hours_breakfast['hour'] = $open_hour;
                            $available_hours_breakfast['hour_name'] = 'Breakfast Hour';

                            $available_hours[] = $available_hours_breakfast;
                        }
                    }

                    $available_hours_lunch = [];

                    if(!is_null($lunch_hours))
                    {
                        $hourdiff = round((strtotime($lunch_hours->end_hour) - strtotime($lunch_hours->start_hour))/3600, 1);
                        $open_hour = Carbon::parse($lunch_hours->start_hour)->format('H:i:s');

                        $max = $hourdiff * 2;
                        for($i=0; $i < $max; $i++)
                        {
                            $opening_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                            //$opening_hour = date('h:i A', strtotime($opening_hour));
                            $open_hour = Carbon::parse($opening_hour)->format('H:i:s');
                            $hour_name = $lunch_hours->hour_name;

                            //$open_hour = $opening_hour;
                            $available_hours_lunch['hour'] = $open_hour;
                            $available_hours_lunch['hour_name'] = 'Lunch Hour';

                            $available_hours[] = $available_hours_lunch;
                        }
                    }

                    if(!is_null($dinner_hours))
                    {
                        $hourdiff = round((strtotime($dinner_hours->end_hour) - strtotime($dinner_hours->start_hour))/3600, 1);
                        $open_hour = Carbon::parse($dinner_hours->start_hour)->format('H:i:s');

                        $max = $hourdiff * 2;
                        for($i=0; $i < $max; $i++)
                        {
                            $opening_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                            //$opening_hour = date('h:i A', strtotime($opening_hour));
                            $open_hour = Carbon::parse($opening_hour)->format('H:i:s');
                            $hour_name = $dinner_hours->hour_name;

                            //$open_hour = $opening_hour;

                            $available_hours_dinner['hour'] = $open_hour;
                            $available_hours_dinner['hour_name'] = 'Dinner Hour';

                            $available_hours[] = $available_hours_dinner;
                        }
                    }

                    $hour_count = count($available_hours);

                }

                elseif(is_null($breakfast_hours) && is_null($lunch_hours) && is_null($dinner_hours))
                {
                    $opening_hour = $restaurant->restaurant_opening_hour;
                    $closing_hour = $restaurant->restaurant_closing_hour;
                    $hourdiff = round((strtotime($closing_hour) - strtotime($opening_hour))/3600, 1);
                    $open_hour = Carbon::parse($opening_hour)->format('H:i:s');

                    $free_hours = array();
                    $available_hours = array();
                    $hour_count = count($available_hours);

                    $max = $hourdiff * 2;
                    for($i=0; $i < $max; $i++)
                    {
                        $opening_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                        //$opening_hour = date('h:i A', strtotime($opening_hour));
                        $open_hour = Carbon::parse($opening_hour)->format('H:i:s');

                        //$open_hour = $opening_hour;
                        $free_hours[] = $open_hour;
                    }
                }

                $number_of_tables = count($tables);

                $available_tables = DB::table('tables')
                    ->where('table_is_booked', 'no')
                    ->where('restaurant_id', $restaurant_id)
                    ->get();

                $available_tables = count($available_tables);
                $available_seats = DB::table('tables')
                    ->where('table_is_booked', 'no')
                    ->where('restaurant_id', $restaurant_id)
                    ->sum('max_covers');

                $tables_reserved = DB::table('tables')
                    ->where('table_is_booked', 'yes')
                    ->where('restaurant_id', $restaurant_id)
                    ->get();

                $tables_reserved = count($tables_reserved);

                $reservations = DB::table('reservations')
                    ->where('date_of_reservation', $date)
                    ->where('restaurant_id', $restaurant_id)
                    ->get();

                $booked = DB::table('reservations')
                    ->where('date_of_reservation', $date)
                    ->where('restaurant_id', $restaurant_id)
                    ->where('reservation_status', 'booked')
                    ->get();
                $booked = count($booked);

                $confirmed = DB::table('reservations')
                    ->where('date_of_reservation', $date)
                    ->where('restaurant_id', $restaurant_id)
                    ->where('reservation_status', 'confirmed')
                    ->get();
                $confirmed = count($confirmed);

                $cancelled = DB::table('reservations')
                    ->where('date_of_reservation', $date)
                    ->where('restaurant_id', $restaurant_id)
                    ->where('reservation_status', 'cancelled')
                    ->get();
                $cancelled = count($cancelled);
                
                $completed = DB::table('reservations')
                    ->where('date_of_reservation', $date)
                    ->where('restaurant_id', $restaurant_id)
                    ->where('reservation_status', 'Completed')
                    ->get();
                $completed = count($completed);
                
                $late = DB::table('reservations')
                    ->where('date_of_reservation', $date)
                    ->where('restaurant_id', $restaurant_id)
                    ->where('reservation_status', 'late')
                    ->get();
                $late = count($late);

                $number_of_reservations = count($reservations);

                $memberships = DB::table('restaurant_memberships')
                    ->where('restaurant_id', $restaurant_id)
                    ->get();
                    
                $guest_types = DB::table('guest_types')
                    ->where('restaurant_id', $restaurant_id)
                    ->get();
                    
                $open_tables = DB::table('tables')
                ->where('table_is_booked', 'no')
                ->where('restaurant_id', $restaurant_id)
                ->where('table_status', '=', 'active')
                ->get();
                
                $open_tables = count($open_tables);

                return view('Admin.Reservation.create',
                    compact('tables', 'restaurants', 'restaurant', 'reservations', 'restaurant_id',
                        'free_tables', 'date', 'available_hours', 'open_tables', 'number_of_tables', 'available_tables', 'available_seats',
                        'tables_reserved', 'number_of_reservations', 'memberships', 'rest_type', 'booked', 'confirmed',
                    'cancelled', 'late', 'guest_types', 'completed', 'hour_count', 'free_hours'))
                    ->with('i');

            }

        }
        else{
            return redirect()->route('login');
        }

    }

    public function reserve_restaurant($id)
    {
        $restaurant = DB::table('restaurants')
                    ->where('id', $id)
                    ->first();

        $restaurant_images = DB::table('restaurants')
            ->join('restaurant_galleries', 'restaurant_galleries.restaurant_id', '=', 'restaurants.id')
            ->select('restaurant_galleries.id', 'restaurant_galleries.Restaurant_name', 'restaurant_galleries.restaurant_image_path',
                'restaurant_galleries.owner_id', 'restaurant_galleries.restaurant_id', 'restaurants.Restaurant_type')
            ->where('restaurants.id', '=', $id)
            ->get();

        //calculate available hours based on the restaurants inputs
        $opening_hour = $restaurant->restaurant_opening_hour;
        $closing_hour = $restaurant->restaurant_closing_hour;
        $duration = $restaurant->Restaurant_duration;
        
        $available_hours_breakfast = [];

        $available_hours_dinner = [];

        $breakfast_hours = DB::table('restaurant_hours')
            ->where('restaurant_id', $id)
            ->where('hour_name', '=', 'Breakfast_Hours')
            ->first();

        $lunch_hours = DB::table('restaurant_hours')
            ->where('restaurant_id', $id)
            ->where('hour_name', '=', 'Lunch_Hours')
            ->first();

        $dinner_hours = DB::table('restaurant_hours')
            ->where('restaurant_id', $id)
            ->where('hour_name', '=', 'Dinner_Hours')
            ->first();

        if(!is_null($breakfast_hours) || !is_null($lunch_hours) || !is_null($dinner_hours))
        {
            $free_hours = array();
            $working_hours = array();

            if(!is_null($breakfast_hours))
            {
                $hourdiff = round((strtotime($breakfast_hours->end_hour) - strtotime($breakfast_hours->start_hour))/3600, 1);
                $open_hour = Carbon::parse($breakfast_hours->start_hour)->format('H:i:s');

                $max = $hourdiff * 2;
                for($i=0; $i < $max; $i++)
                {
                    $opening_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                    //$opening_hour = date('h:i A', strtotime($opening_hour));
                    $open_hour = Carbon::parse($opening_hour)->format('H:i:s');
                    $hour_name = $breakfast_hours->hour_name;

                    //$open_hour = $opening_hour;
                    $available_hours_breakfast['hour'] = $open_hour;
                    $available_hours_breakfast['hour_name'] = 'Breakfast Hour';

                    $working_hours[] = $available_hours_breakfast;
                }
            }

            $available_hours_lunch = [];

            if(!is_null($lunch_hours))
            {
                $hourdiff = round((strtotime($lunch_hours->end_hour) - strtotime($lunch_hours->start_hour))/3600, 1);
                $open_hour = Carbon::parse($lunch_hours->start_hour)->format('H:i:s');

                $max = $hourdiff * 2;
                for($i=0; $i < $max; $i++)
                {
                    $opening_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                    //$opening_hour = date('h:i A', strtotime($opening_hour));
                    $open_hour = Carbon::parse($opening_hour)->format('H:i:s');
                    $hour_name = $lunch_hours->hour_name;

                    //$open_hour = $opening_hour;
                    $available_hours_lunch['hour'] = $open_hour;
                    $available_hours_lunch['hour_name'] = 'Lunch Hour';

                    $working_hours[] = $available_hours_lunch;
                }
            }

            if(!is_null($dinner_hours))
            {
                $hourdiff = round((strtotime($dinner_hours->end_hour) - strtotime($dinner_hours->start_hour))/3600, 1);
                $open_hour = Carbon::parse($dinner_hours->start_hour)->format('H:i:s');

                $max = $hourdiff * 2;
                for($i=0; $i < $max; $i++)
                {
                    $opening_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                    //$opening_hour = date('h:i A', strtotime($opening_hour));
                    $open_hour = Carbon::parse($opening_hour)->format('H:i:s');
                    $hour_name = $dinner_hours->hour_name;

                    //$open_hour = $opening_hour;

                    $available_hours_dinner['hour'] = $open_hour;
                    $available_hours_dinner['hour_name'] = 'Dinner Hour';

                    $working_hours[] = $available_hours_dinner;
                }
            }

            //$collection = collect($available_hours_breakfast);
            //$merged = $collection->merge();
            //$merged = $collection->merge($available_hours_lunch);
            //$available_hours = $merged->merge($available_hours_dinner);
            //$available_hours->all();

            $hour_count = count($working_hours);

        }

        elseif(is_null($breakfast_hours) && is_null($lunch_hours) && is_null($dinner_hours))
        {
            $opening_hour = $restaurant->restaurant_opening_hour;
            $closing_hour = $restaurant->restaurant_closing_hour;
            $hourdiff = round((strtotime($closing_hour) - strtotime($opening_hour))/3600, 1);
            $open_hour = Carbon::parse($opening_hour)->format('H:i:s');
            
            $free_hours = array();
            $working_hours = array();
            $hour_count = count($working_hours);

            $max = $hourdiff * 2;
            for($i=0; $i < $max; $i++)
            {
                $opening_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                //$opening_hour = date('h:i A', strtotime($opening_hour));
                $open_hour = Carbon::parse($opening_hour)->format('H:i:s');

                //$open_hour = $opening_hour;
                $free_hours[] = $open_hour;
            }
            
        }

        return view('Reservation.restaurant', compact('restaurant', 'working_hours', 'restaurant_images', 'hour_count', 'free_hours'));
    }

    public function get_coordinates($id)
    {
        $restaurant = DB::table('restaurants')
            ->where('id', $id)
            ->first();

        $position = $restaurant->Restaurant_coordinates;
        $coordinates = explode(',', $position);
        $latitude = $coordinates[0];
        $lat = 'lat: '.$latitude;
        $longitude = $coordinates[1];
        //$lng = 'lng:'.$longitude;
        $lng = ltrim($longitude, ' ');

        $position = [$latitude, $lng];
        $map_address = json_encode($position);
        //var_dump($map_address);

        //die();
        return $map_address;
    }

    public function check_restaurant(Request $request)
    {
        $request->validate([
            'reservation_date' => 'required|date|after:yesterday',
            'reservation_time' => 'required|date_format:H:i:s',
            'number_of_people' => 'required',
            'restaurant_id' => 'required|int'
        ]);

        /**perform check on the time and then cancel a reservation if not confirmed
        $reservations = DB::table('reservations')
            ->where('reservation_status', '=', 'booked')
            ->where('time_of_reservation', '<',Carbon::parse('-48 hours'))
            ->get();

        foreach ($reservations as $reservation)
        {
            if($reservation->reservation_status != "confirmed")
            {
                $reservation_id = $reservation->id;

                DB::table('reservations')
                    ->where('id', $reservation_id)
                    ->update([ 'reservation_status' => 'cancelled']);
            }
        }*/

        //checking table availability based on the date
        $restaurant_id = $request->restaurant_id;
        $date = date('Y-m-d');
        $all_reservations = DB::table('reservations')
            ->join('table__to__reservations', 'table__to__reservations.reservation_code', '=', 'reservations.reservation_code')
            ->join('tables', 'tables.id', '=', 'table__to__reservations.table_id')
            ->select('reservations.reservation_code', 'reservations.reservation_duration', 'reservations.time_of_reservation')
            ->where('reservations.restaurant_id', $restaurant_id)
            ->where('reservations.date_of_reservation', $date)
            ->get();

        foreach ($all_reservations as $single_reservation){

            $reservation_duration = $single_reservation->reservation_duration;
            $reserved_time = Carbon::parse($single_reservation->time_of_reservation)->format('H:i:s');
            $now = date('H:i:s');
            $current_time = Carbon::parse($now)->format('H:i:s');
            $reserved_hours = Carbon::createFromFormat('H:i:s',$reserved_time)->addMinutes($reservation_duration);
            $reserved_hours = Carbon::parse($reserved_hours)->format('H:i:s');

            //checking if the total reservation time is over
            if($reserved_hours < $current_time)
            {
                $code = $single_reservation->reservation_code;
                $reserved_tables = DB::table('table__to__reservations')
                    ->where('reservation_code', $code)->get();
                foreach ($reserved_tables as $reserved_table)
                {
                    $tables = DB::table('tables')
                        ->where('id', $reserved_table->table_id)
                        ->update([ 'table_is_booked' => 'no']);
                }
            }

        }

        $date = $request->reservation_date;
        $time = $request->reservation_time;
        $restaurant_id = $request->restaurant_id;
        $no_of_people = $request->number_of_people;
        //$reserve_time = Carbon::createFromFormat('H:i:s', $request->reservation_time);

        $restaurant = DB::table('restaurants')
            ->where('id', $restaurant_id)
            ->first();

        $day_name = Carbon::parse($date)->dayOfWeek;

        if($restaurant->sunday_open == 'no' && $day_name == 0)
        {
            return response()->json(
                array('success' => true, 'value' => 'closed', 'message' => 'Restaurant is Closed on Sundays.'));
        }
        elseif($restaurant->monday_open == 'no' && $day_name == 1)
        {
            return response()->json(
                array('success' => true, 'value' => 'closed', 'message' => 'Restaurant is Closed on Mondays.'));
        }
        elseif($restaurant->tuesday_open == 'no' && $day_name == 2)
        {
            return response()->json(
                array('success' => true, 'value' => 'closed', 'message' => 'Restaurant is Closed on Tuesdays.'));
        }
        elseif($restaurant->wednesday_open == 'no' && $day_name == 3)
        {
            return response()->json(
                array('success' => true, 'value' => 'closed', 'message' => 'Restaurant is Closed on Wednesdays.'));
        }
        elseif($restaurant->thursday_open == 'no' && $day_name == 4)
        {
            return response()->json(
                array('success' => true, 'value' => 'closed', 'message' => 'Restaurant is Closed on Thursdays.'));
        }
        elseif($restaurant->friday_open == 'no' && $day_name == 5)
        {
            return response()->json(
                array('success' => true, 'value' => 'closed', 'message' => 'Restaurant is Closed on Fridays.'));
        }
        elseif($restaurant->saturday_open == 'no' && $day_name == 6)
        {
            return response()->json(
                array('success' => true, 'value' => 'closed', 'message' => 'Restaurant is Closed on Saturdays.'));
        }

        $closing_hour = $restaurant->restaurant_closing_hour;
        //$closing_hour = Carbon::createFromFormat('H:i:s', $restaurant->restaurant_closing_hour);
        $opening_hour = $restaurant->restaurant_opening_hour;
        $duration = $restaurant->Restaurant_duration;
        //$opening_hour = Carbon::createFromFormat('H:i:s', $restaurant->restaurant_opening_hour);
        $restaurant_max_capacity = $restaurant->Restaurant_max_capacity;
        
        if($duration == '')
        {
            $duration = '30';
        }
        else{
            $duration = $duration;
        }

        $booked_seats = DB::table('reservations')
            ->join('table__to__reservations', 'table__to__reservations.reservation_code',
                '=', 'reservations.reservation_code')
            ->where('reservations.restaurant_id', $restaurant_id)
            ->where('reservations.reservation_status', 'LIKE', 'confirmed')
            ->where('reservations.date_of_reservation', 'LIKE',$date)
            ->where('reservations.time_of_reservation', 'LIKE',$time)
            ->sum('reservations.number_of_people');

        $available_seats = $restaurant_max_capacity - $booked_seats;

        $check_table = DB::table('reservations')
            ->where('restaurant_id', $restaurant_id)
            ->where('date_of_reservation', $date)
            ->where('time_of_reservation', $time)
            ->get();

        $empty_tables = DB::table('tables')
                        ->where('restaurant_id', $restaurant_id)
                        ->where('table_is_booked', 'no')
                        ->where('table_status', 'active')
                        ->get();

        $count_empty = count($empty_tables);

        $reservation_count = count($check_table);
        
        if($time < $opening_hour || $time > $closing_hour)
        {
            return response()->json(array('success' => true, 'value' => 'closed', 'message' => 'Restaurant is closed at this time.'));
        }
        elseif ($no_of_people > $restaurant_max_capacity)
        {
            $available_hours = array();
            $duration = $restaurant->Restaurant_duration;

            $open_hour = Carbon::parse($time)->format('H:i:s');

            for($i=0; $i < 4; $i++)
            {
                $open_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                $open_hour = Carbon::parse($open_hour)->format('H:i:s');

                $available_hours[] = $open_hour;
            }
            $available_hours = json_encode($available_hours);
                
            return response()->json(array('success' => true, 'available_hours' => $available_hours, 'value' => 'all_booked', 'message' => 'All Tables are Booked.'));
        }
        else{
            if ($reservation_count == 0) {
                $available_hours = array();
                $duration = $restaurant->Restaurant_duration;

                $open_hour = Carbon::parse($time)->format('H:i:s');

                for($i=0; $i < 4; $i++)
                {
                    $open_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                    $open_hour = Carbon::parse($open_hour)->format('H:i:s');

                    $available_hours[] = $open_hour;
                }
                $available_hours = json_encode($available_hours);
                return response()->json(array('success' => true, 'value' => 'empty', 'message' => 'Table is Available.'));
            }
            elseif ($reservation_count > 0 && $count_empty > $reservation_count) {
                $available_hours = array();
                $duration = $restaurant->Restaurant_duration;

                $open_hour = Carbon::parse($time)->format('H:i:s');

                for($i=0; $i < 4; $i++)
                {
                    $open_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                    $open_hour = Carbon::parse($open_hour)->format('H:i:s');

                    $available_hours[] = $open_hour;
                }
                $available_hours = json_encode($available_hours);
                return response()->json(array('success' => true, 'value' => 'empty', 'message' => 'Table is Available.'));
            }
            elseif ($reservation_count > 0 && $reservation_count == $count_empty){
                $available_hours = array();
                $duration = $restaurant->Restaurant_duration;

                $open_hour = Carbon::parse($time)->format('H:i:s');

                for($i=0; $i < 6; $i++)
                {
                    $open_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                    $open_hour = Carbon::parse($open_hour)->format('H:i:s');

                    $available_hours[] = $open_hour;
                }
                $available_hours = json_encode($available_hours);
                return response()->json(array('success' => true, 'available_hours' => $available_hours, 'value' => 'all_booked', 'message' => 'All Tables are Booked1.'));
            }
            elseif ($reservation_count > 0 && $reservation_count > $count_empty){
                $available_hours = array();
                $duration = $restaurant->Restaurant_duration;

                $open_hour = Carbon::parse($time)->format('H:i:s');

                for($i=0; $i < 6; $i++)
                {
                    $open_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                    $open_hour = Carbon::parse($open_hour)->format('H:i:s');

                    $available_hours[] = $open_hour;
                }
                $available_hours = json_encode($available_hours);
                return response()->json(array('success' => true, 'available_hours' => $available_hours, 'value' => 'all_booked', 'message' => 'All Tables are Booked2.'));
            }
            else
            {
                $available_hours = array();
                $duration = $restaurant->Restaurant_duration;

                $open_hour = Carbon::parse($time)->format('H:i:s');

                for($i=0; $i < 4; $i++)
                {
                    $open_hour = Carbon::createFromFormat('H:i:s',$open_hour)->addMinutes($duration);
                    $open_hour = Carbon::parse($open_hour)->format('H:i:s');

                    $available_hours[] = $open_hour;
                }
                $available_hours = json_encode($available_hours);
                return response()->json(array('success' => true, 'available_hours' => $available_hours, 'value' => 'all_booked', 'message' => 'All Tables are Booked3.'));
            }
            
        }

        return response()->json(array('success' => false, 'value' => 'error', 'message' => 'Something is wrong with your inputs, check again.'));
    }

    public function book_restaurant(Request $request)
    {
        $request->validate([
            'reservation_date' => 'required|date',
            'reservation_time' => 'required|date_format:H:i:s',
            'number_of_people' => 'required',
            'restaurant_id' => 'required|int',
            'reservation_type' => 'required|string',
            'user_name' => 'required|string|max:50',
            'phone_number' => 'required|string|min:10|max:13',
            'user_email' => 'required|email|max:50',
            'status' => 'required|string'
        ]);
        if($request->reserver_message != "")
        {
            $request->validate([
                'reserver_message' => 'required|string|max:500'
            ]);
        }

        $restaurant_id = $request->restaurant_id;

        $restaurant = DB::table('restaurants')
            ->where('id', $restaurant_id)
            ->first();
            
        $total_reservations = DB::table('reservations')
            ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
            ->where('reservations.restaurant_id', $restaurant->id)
            ->where('reservations.date_of_reservation', $request->reservation_date)
            ->where('reservations.time_of_reservation', $request->reservation_time)
            ->get();
            
        $empty_tables = DB::table('tables')
            ->where('restaurant_id', $restaurant_id)
            ->where('table_is_booked', '=', 'no')
            ->where('table_status', '=', 'active')
            ->get();
            
        $reservation_count = count($total_reservations);

        $count_empty = count($empty_tables);

        /**if($count_empty > 0)
        {
            $status = $request->status;
        }
        else{
            $status = $request->status;
        }*/
        if ($reservation_count == 0) {
            $status = 'booked';
        }
        elseif ($reservation_count > 0 && $count_empty > $reservation_count) {
            $status = 'booked';
        }
        elseif ($reservation_count > 0 && $reservation_count == $count_empty){
            $status = 'wait_list';
        }
        elseif ($reservation_count > 0 && $reservation_count > $count_empty){
            $status = 'wait_list';
        }
        else
        {
            $status = 'wait_list';
        }

        $restaurant_name = $restaurant->Restaurant_name;
        $Restaurant_email = $restaurant->Restaurant_email;
        $registered = DB::table('users')
            ->where('email', $request->user_email)
            ->get();
            

        $reg_user = count($registered);

        $reservation_code = Str::random(10);

        $user = DB::table('guests')
            ->where('email', $request->user_email)
            ->where('restaurant_id', $restaurant_id)
            ->first();

        if (is_null($user)) {

            $data = new Guest;

            $data->user_name = $request->user_name;
            $data->email = $request->user_email;
            $data->phone_number = $request->phone_number;
            $data->restaurant_id = $restaurant_id;
            $data->save();

            $guest_id = $data->id;

            $info = new Reservation;

            $info->reservation_code = $reservation_code;
            $info->date_of_reservation = $request->reservation_date;
            $info->time_of_reservation = $request->reservation_time;
            $info->number_of_people = $request->number_of_people;
            $info->reserver_message = $request->reserver_message;
            $info->user_id = $guest_id;
            $info->user_name = $request->user_name;
            $info->user_email = $request->user_email;
            $info->user_phone = $request->phone_number;
            $info->restaurant_id = $restaurant_id;
            $info->restaurant_name = $restaurant_name;
            $info->reservation_status = $status;
            $info->reservation_type = $request->reservation_type;

            $info->save();

            if($reg_user > 0 && Auth::check()){
                
                $data = [
                        'subject' => 'Reservation Update.',
                        'email' => $request->user_email,
                        'status' => $status,
                        'date' => $request->reservation_date,
                        'time' => $request->reservation_time,
                        'guest_name' => $request->user_name,
                        'reservation_code' => $reservation_code,
                        'Restaurant_name' => $restaurant_name,
                        'no_of_people' => $request->number_of_people,
                        'restaurant_id' => $restaurant->id,
                        'Restaurant_email' => $Restaurant_email,
                        'Additional_note' => $request->reserver_message
                    ];

                    Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                        $message->to($data['Restaurant_email'])
                            ->subject($data['subject']);
                    });
                    
                    Mail::send('Emails.bookedEmail', $data, function($message) use ($data) {
                        $message->to($data['email'])
                            ->subject($data['subject']);
                    });

                    if (Mail::failures()) {
                        return response()->json(array('success' => true, 'value' => 'Table Booked', 'registered' => 'logged_user',
                    'message' => 'Table Booked Successfully for '.$request->reservation_date.' at '. $request->reservation_time));
                    }else{
                        return response()->json(array('success' => true, 'value' => 'Table Booked', 'registered' => 'logged_user',
                    'message' => 'Table Booked Successfully for '.$request->reservation_date.' at '. $request->reservation_time));
                    }
                //return response()->json(array('success' => true, 'value' => 'Table Booked', 'registered' => 'logged_user',
                    //'message' => 'Table Booked Successfully for '.$request->reservation_date.' at '. $request->reservation_time));
            }
            elseif($reg_user > 0){
                
                $data = [
                        'subject' => 'Reservation Update.',
                        'email' => $request->user_email,
                        'status' => $status,
                        'date' => $request->reservation_date,
                        'time' => $request->reservation_time,
                        'guest_name' => $request->user_name,
                        'reservation_code' => $reservation_code,
                        'Restaurant_name' => $restaurant_name,
                        'no_of_people' => $request->number_of_people,
                        'restaurant_id' => $restaurant->id,
                        'Restaurant_email' => $Restaurant_email,
                        'Additional_note' => $request->reserver_message
                    ];

                    Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                        $message->to($data['Restaurant_email'])
                            ->subject($data['subject']);
                    });
                    
                    Mail::send('Emails.bookedEmail', $data, function($message) use ($data) {
                        $message->to($data['email'])
                            ->subject($data['subject']);
                    });

                    if (Mail::failures()) {
                        return response()->json(array('success' => true, 'value' => 'Table Booked', 'registered' => 'yes',
                    'message' => 'Table Booked Successfully for '.$request->reservation_date.' at '. $request->reservation_time));
                    }else{
                        return response()->json(array('success' => true, 'value' => 'Table Booked', 'registered' => 'yes',
                    'message' => 'Table Booked Successfully for '.$request->reservation_date.' at '. $request->reservation_time));
                    }
                /**return response()->json(array('success' => true, 'value' => 'Table Booked', 'registered' => 'yes',
                    'message' => 'Table Booked Successfully for '.$request->reservation_date.' at '. $request->reservation_time));*/
            }
            else{
                
                $data = [
                        'subject' => 'Reservation Update.',
                        'email' => $request->user_email,
                        'status' => $status,
                        'date' => $request->reservation_date,
                        'time' => $request->reservation_time,
                        'guest_name' => $request->user_name,
                        'reservation_code' => $reservation_code,
                        'Restaurant_name' => $restaurant_name,
                        'no_of_people' => $request->number_of_people,
                        'restaurant_id' => $restaurant->id,
                        'Restaurant_email' => $Restaurant_email,
                        'Additional_note' => $request->reserver_message
                    ];

                    Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                        $message->to($data['Restaurant_email'])
                            ->subject($data['subject']);
                    });
                    
                    Mail::send('Emails.bookedEmail', $data, function($message) use ($data) {
                        $message->to($data['email'])
                            ->subject($data['subject']);
                    });

                    if (Mail::failures()) {
                        return response()->json(array('success' => true, 'value' => 'Table Booked', 'registered' => 'no',
                    'message' => 'Table Booked Successfully for '.$request->reservation_date.' at '. $request->reservation_time));
                    }else{
                        return response()->json(array('success' => true, 'value' => 'Table Booked', 'registered' => 'no',
                    'message' => 'Table Booked Successfully for '.$request->reservation_date.' at '. $request->reservation_time));
                    }
                /**return response()->json(array('success' => true, 'value' => 'Table Booked', 'registered' => 'no',
                    'message' => 'Table Booked Successfully for '.$request->reservation_date.' at '. $request->reservation_time));*/
            }
        }
        else{
            $user_name = $user->user_name;
            $guest_id = $user->id;
            $user_email = $user->email;
            $phone_number = $user->phone_number;

            $info = new Reservation;

            $info->reservation_code = $reservation_code;
            $info->date_of_reservation = $request->reservation_date;
            $info->time_of_reservation = $request->reservation_time;
            $info->number_of_people = $request->number_of_people;
            $info->reserver_message = $request->reserver_message;
            $info->user_id = $guest_id;
            $info->user_email = $user_email;
            $info->user_phone = $phone_number;
            $info->user_name = $user_name;
            $info->restaurant_id = $restaurant_id;
            $info->restaurant_name = $restaurant_name;
            $info->reservation_status = $status;

            $info->save();

            if($reg_user > 0 && Auth::check()){
                
                $data = [
                        'subject' => 'Reservation Update.',
                        'email' => $request->user_email,
                        'status' => $status,
                        'date' => $request->reservation_date,
                        'time' => $request->reservation_time,
                        'guest_name' => $request->user_name,
                        'reservation_code' => $reservation_code,
                        'Restaurant_name' => $restaurant_name,
                        'no_of_people' => $request->number_of_people,
                        'restaurant_id' => $restaurant->id,
                        'Restaurant_email' => $Restaurant_email,
                        'Additional_note' => $request->reserver_message
                    ];

                    Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                        $message->to($data['Restaurant_email'])
                            ->subject($data['subject']);
                    });
                    
                    Mail::send('Emails.bookedEmail', $data, function($message) use ($data) {
                        $message->to($data['email'])
                            ->subject($data['subject']);
                    });

                    if (Mail::failures()) {
                        return response()->json(array('success' => true, 'value' => 'Table Booked', 'registered' => 'logged_user',
                    'message' => 'Table Booked Successfully for '.$request->reservation_date.' at '. $request->reservation_time));
                    }else{
                        return response()->json(array('success' => true, 'value' => 'Table Booked', 'registered' => 'logged_user',
                    'message' => 'Table Booked Successfully for '.$request->reservation_date.' at '. $request->reservation_time));
                    }
                    
                /**return response()->json(array('success' => true, 'value' => 'Table Booked', 'registered' => 'logged_user',
                    'message' => 'Table Booked Successfully for '.$request->reservation_date.' at '. $request->reservation_time));*/
            }
            elseif($reg_user > 0){
                
                $data = [
                        'subject' => 'Reservation Update.',
                        'email' => $request->user_email,
                        'status' => $status,
                        'date' => $request->reservation_date,
                        'time' => $request->reservation_time,
                        'guest_name' => $request->user_name,
                        'reservation_code' => $reservation_code,
                        'Restaurant_name' => $restaurant_name,
                        'no_of_people' => $request->number_of_people,
                        'restaurant_id' => $restaurant->id,
                        'Restaurant_email' => $Restaurant_email,
                        'Additional_note' => $request->reserver_message
                    ];

                    Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                        $message->to($data['Restaurant_email'])
                            ->subject($data['subject']);
                    });
                    
                    Mail::send('Emails.bookedEmail', $data, function($message) use ($data) {
                        $message->to($data['email'])
                            ->subject($data['subject']);
                    });

                    if (Mail::failures()) {
                        return response()->json(array('success' => true, 'value' => 'Table Booked', 'registered' => 'yes',
                    'message' => 'Table Booked Successfully for '.$request->reservation_date.' at '. $request->reservation_time));
                    }else{
                        return response()->json(array('success' => true, 'value' => 'Table Booked', 'registered' => 'yes',
                    'message' => 'Table Booked Successfully for '.$request->reservation_date.' at '. $request->reservation_time));
                    }
                    
                /**return response()->json(array('success' => true, 'value' => 'Table Booked', 'registered' => 'yes',
                    'message' => 'Table Booked Successfully for '.$request->reservation_date.' at '. $request->reservation_time));*/
            }
            else{
                
                $data = [
                        'subject' => 'Reservation Update.',
                        'email' => $request->user_email,
                        'status' => $status,
                        'date' => $request->reservation_date,
                        'time' => $request->reservation_time,
                        'guest_name' => $request->user_name,
                        'reservation_code' => $reservation_code,
                        'Restaurant_name' => $restaurant_name,
                        'no_of_people' => $request->number_of_people,
                        'restaurant_id' => $restaurant->id,
                        'Restaurant_email' => $Restaurant_email,
                        'Additional_note' => $request->reserver_message
                    ];

                    Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                        $message->to($data['Restaurant_email'])
                            ->subject($data['subject']);
                    });
                    
                    Mail::send('Emails.bookedEmail', $data, function($message) use ($data) {
                        $message->to($data['email'])
                            ->subject($data['subject']);
                    });

                    if (Mail::failures()) {
                        return response()->json(array('success' => true, 'value' => 'Table Booked', 'registered' => 'no',
                    'message' => 'Table Booked Successfully for '.$request->reservation_date.' at '. $request->reservation_time));
                    }else{
                        return response()->json(array('success' => true, 'value' => 'Table Booked', 'registered' => 'no',
                    'message' => 'Table Booked Successfully for '.$request->reservation_date.' at '. $request->reservation_time));
                    }
                    
                /**return response()->json(array('success' => true, 'value' => 'Table Booked', 'registered' => 'no',
                    'message' => 'Table Booked Successfully for '.$request->reservation_date.' at '. $request->reservation_time));*/
            }
        }

    }

    public function check_availability(Request $request)
    {
        $request->validate([
            'reservation_date' => 'required|date|after:yesterday',
            'reservation_time' => 'required|date_format:H:i:s',
            'number_of_people' => 'required',
            'Restaurant_name' => 'required|string|max:50'
        ]);

        $date = $request->reservation_date;
        $time = $request->reservation_time;
        $restaurant_name = $request->Restaurant_name;
        $no_of_people = $request->number_of_people;

        $find_restaurant = Restaurant::where('Restaurant_name', 'LIKE', '%'.$restaurant_name.'%')->first();

        if (is_null($find_restaurant))
        {
            return response()->json(array('success' => true, 'value' => 'restaurant not found', 'message' => 'No Restaurant in our system with the name provided.'));
        }
        elseif(!is_null($find_restaurant))
        {
            $restaurant_id = $find_restaurant->id;
            //$restaurant_opening_hour = $find_restaurant->restaurant_opening_hour;
            $restaurant_opening_hour = $find_restaurant->restaurant_opening_hour;
            //$restaurant_closing_hour = $find_restaurant->restaurant_opening_hour;
            $restaurant_closing_hour = $find_restaurant->restaurant_closing_hour;
            //$reserved_time = date_format(date_create($time), 'H:i:s');
            $duration = $find_restaurant->Restaurant_duration;

            /**if($time < $restaurant_opening_hour || $time > $restaurant_closing_hour)
            {
                return response()->json(array('success' => true, 'value' => 'closed', 'message' => 'Restaurant is closed at this time.'));
            }*/

            $max_capacity = $find_restaurant->Restaurant_max_capacity;

            $booked_seats = DB::table('reservations')
                            ->where('restaurant_id', $restaurant_id)
                            ->where('reservation_status', 'confirmed')
                            ->where('date_of_reservation', $date)
                            ->where('time_of_reservation', $time)
                            ->sum('number_of_people');

            $restaurant = DB::table('restaurants')->where('id', $restaurant_id)->first();

            $day_name = Carbon::parse($date)->dayOfWeek;

            if($restaurant->sunday_open == 'no' && $day_name == 0)
            {
                return response()->json(
                    array('success' => true, 'value' => 'closed', 'message' => 'Restaurant is Closed on Sundays.'));
            }
            elseif($restaurant->monday_open == 'no' && $day_name == 1)
            {
                return response()->json(
                    array('success' => true, 'value' => 'closed', 'message' => 'Restaurant is Closed on Mondays.'));
            }
            elseif($restaurant->tuesday_open == 'no' && $day_name == 2)
            {
                return response()->json(
                    array('success' => true, 'value' => 'closed', 'message' => 'Restaurant is Closed on Tuesdays.'));
            }
            elseif($restaurant->wednesday_open == 'no' && $day_name == 3)
            {
                return response()->json(
                    array('success' => true, 'value' => 'closed', 'message' => 'Restaurant is Closed on Wednesdays.'));
            }
            elseif($restaurant->thursday_open == 'no' && $day_name == 4)
            {
                return response()->json(
                    array('success' => true, 'value' => 'closed', 'message' => 'Restaurant is Closed on Thursdays.'));
            }
            elseif($restaurant->friday_open == 'no' && $day_name == 5)
            {
                return response()->json(
                    array('success' => true, 'value' => 'closed', 'message' => 'Restaurant is Closed on Fridays.'));
            }
            elseif($restaurant->saturday_open == 'no' && $day_name == 6)
            {
                return response()->json(
                    array('success' => true, 'value' => 'closed', 'message' => 'Restaurant is Closed on Saturdays.'));
            }

            $available_seats = $max_capacity - $booked_seats;

            $check_table = Reservation::where('date_of_reservation','LIKE','%'.$date.'%')
                ->Where('time_of_reservation','LIKE','%'.$time.'%')
                ->where('restaurant_id', 'LIKE', '%'.$restaurant_id.'%')
                ->get();

            $table_count = count($check_table);
            //$two_hours = date_format(date_create('02:00'), 'H:i');

            if($time < $restaurant_opening_hour || $time > $restaurant_closing_hour)
            {
                return response()->json(array('success' => true, 'data' => $restaurant_id, 'value' => 'closed', 'message' => 'Restaurant is closed at this time.'));
            }
            elseif ($no_of_people > $available_seats)
            {
                return response()->json(array('success' => true, 'data' => $restaurant_id, 'value' => 'full', 'message' => 'Number of people is greater than Restaurant Capacity.'));
            }
            elseif ($no_of_people > $max_capacity)
            {
                return response()->json(array('success' => true, 'data' => $restaurant_id, 'value' => 'full', 'message' => 'Number of people is greater than Restaurant Capacity.'));
            }
            else{
                if ($table_count == 0) {

                    //reset the session data to save the new keys
                    session()->forget('reserve_data');

                    //$reserve_data = session()->get('reserve_data', []);

                    $reserve_data[$restaurant_id] = [
                        "reserved_date" => $date,
                        "reserved_time" => $time,
                        "number_of_people" => $no_of_people
                    ];

                    session()->put('reserve_data', $reserve_data);

                    //$request->session()->put();

                    //return redirect()->route('reserve_restaurant', $restaurant_id);

                    $route = "reserve_restaurant/$restaurant_id";
                    return response()->json(array('success' => true, 'route' => $route, 'date' => $date, 'time' => $time,
                        'no_of_people' => $no_of_people, 'data' => $duration, 'value' => 'empty',
                        'message' => 'Tables are Available Continue to Booking.'));
                }
                elseif ($table_count > 0){
                    return response()->json(array('success' => true, 'value' => 'booked', 'message' => 'Table is Booked try Another.'));
                }
            }
        }

        return response()->json(array('success' => false, 'value' => 'error', 'message' => 'Something is wrong with your inputs, check again.'));
    }

    public function book_table(Request $request)
    {
        $request->validate([
            'reservation_date' => 'required|date|after:yesterday',
            'reservation_time' => 'required|date_format:H:i:s',
            'number_of_people' => 'required',
            'reserver_message' => 'required|string|max:500',
            'Restaurant_name' => 'required|string|max:50',
            'user_name' => 'required|string|max:50',
            'phone_number' => 'required|string|min:10|max:13',
            'user_email' => 'required|email|max:50'
        ]);

        $rest_name = $request->Restaurant_name;

        $find_restaurant = Restaurant::where('Restaurant_name', 'LIKE', '%' . $rest_name . '%')->first();
        $restaurant_id = $find_restaurant->id;
        $restaurant_name = $find_restaurant->Restaurant_name;
        //$reserver_id = Auth::user()->id;
        $reservation_code = Str::random(10);

        $user = DB::table('guests')
            ->where('email', $request->user_email)
            ->where('restaurant_id', $restaurant_id)
            ->first();

        if (is_null($user)){

            $data = new Guest;

            $data->user_name = $request->user_name;
            $data->email = $request->user_email;
            $data->phone_number = $request->phone_number;
            $data->restaurant_id = $restaurant_id;
            $data->save();

            $guest_id = $data->id;

            $info = new Reservation;

            $info->reservation_code = $reservation_code;
            $info->date_of_reservation = $request->reservation_date;
            $info->time_of_reservation = $request->reservation_time;
            $info->number_of_people = $request->number_of_people;
            $info->reserver_message = $request->reserver_message;
            $info->user_id = $guest_id;
            $info->user_email = $request->user_email;
            $info->user_name = $request->user_name;
            $info->user_phone = $request->phone_number;
            $info->restaurant_id = $restaurant_id;
            $info->restaurant_name = $restaurant_name;
            $info->reservation_status = 'booked';

            $info->save();

            return response()->json(array('success' => true, 'value' => 'Table Booked',
                'message' => 'Table Booked Successfully.'));
        }
        else{
            $user_name = $user->user_name;
            $guest_id = $user->id;
            $user_email = $user->email;
            $phone_number = $user->phone_number;

            $info = new Reservation;

            $info->reservation_code = $reservation_code;
            $info->date_of_reservation = $request->reservation_date;
            $info->time_of_reservation = $request->reservation_time;
            $info->number_of_people = $request->number_of_people;
            $info->reserver_message = $request->reserver_message;
            $info->user_id = $guest_id;
            $info->user_email = $user_email;
            $info->user_phone = $phone_number;
            $info->user_name = $user_name;
            $info->restaurant_id = $restaurant_id;
            $info->restaurant_name = $restaurant_name;
            $info->reservation_status = 'booked';

            $info->save();

            return response()->json(array('success' => true, 'value' => 'Table Booked',
                'message' => 'Table Booked Successfully.'));

            /**Mail::to('biniam@ccsethiopia.com')->send(new NotifyMail());

            if (Mail::failures()) {
                return response()->json(array('error' => true, 'message' => 'Sorry! Please try again latter.'));
            }else{
                return response()->json(array('success' => true, 'value' => 'Table Booked',
                    'message' => 'Table Booked Successfully.'));
            }*/

        }

    }

    public function table_reservations($id)
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

        $restaurant = DB::table('restaurants')
            ->where('id', '=', $restaurant_id)
            ->first();

        $reservations = DB::table('reservations')
                        ->where('restaurant_id', $restaurant_id)
                        ->orderByDesc('created_at')
                        ->get();

        return view('Admin.Reservation.show_reservations', compact('reservations', 'restaurant_id', 'restaurants', 'restaurant'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function edit(Reservation $reservation)
    {
        if(Auth::check()){
            $user_id = Auth::user()->id;

            $restaurant = DB::table('restaurants')
                ->select('id', 'Restaurant_name')
                ->where('user_id', $user_id)
                ->first();

            $restaurant_id = $restaurant->id;

            $tables = DB::table('tables')
                      ->where('restaurant_id', $restaurant_id)
                      ->where('table_is_booked', '=', 'no')
                      ->get();

            return view('Admin.Reservation.edit_reservation', compact('reservation', 'tables'));

        }
        else{
            return redirect()->route('login');
        }
    }
    
    public function get_reservation($id)
    {
        if(Auth::check())
        {
            $reservation = DB::table('reservations')
                ->where('id', $id)
                ->first();

            return json_encode($reservation);
        }
    }

    public function grid($id)
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

        $restaurant = DB::table('restaurants')
            ->where('id', '=', $restaurant_id)
            ->first();

        $reservations = DB::table('reservations')
            ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
            ->select('reservations.reservation_code AS id', 'reservations.user_name AS title',
                'reservations.number_of_people AS description', 'restaurants.Restaurant_name',
                DB::raw("CONCAT(reservations.date_of_reservation,' ',reservations.time_of_reservation) AS start"))
            ->where('reservations.restaurant_id', $restaurant_id)
            ->get();

        if(count($reservations) > 0)
        {
            $calendar_data = json_encode($reservations);
        }
        else{
            $calendar_data = "";
            $calendar_data = json_encode($calendar_data);
        }

        return view('Admin.Reservation.grid', compact('restaurant_id', 'restaurants', 'restaurant', 'calendar_data'));
    }

    public function grid_data($id)
    {
        $restaurant_id = $id;

        $reservations = DB::table('reservations')
            ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
            ->select('reservations.reservation_code', 'reservations.user_name', 'reservations.date_of_reservation',
                'reservations.time_of_reservation', 'reservations.number_of_people', 'restaurants.Restaurant_name')
            ->where('reservations.restaurant_id', $restaurant_id)
            ->get();

        $data = [];
        $grid_data = array();

        foreach ($reservations as $reservation){

            $start_date = $reservation->date_of_reservation . $reservation->time_of_reservation;
            $start = date_format(date_create($start_date), 'Y-m-d H:i');
            $title = $reservation->user_name.' - '.$reservation->Restaurant_name;

            $data['id'] = $reservation->reservation_code;
            $data['title'] = $title;
            $data['start'] = $start;
            $data['end'] = $start;
            $data['description'] = $reservation->number_of_people;
            $data['allDay'] = false;
            $data['backgroundColor'] = #0065A3;
            $data['color'] = #fff;

            $grid_data[] = $data;

        }
        //var_dump($grid_data);
        //die();
        //return json_encode($grid_data);

    }

    public function update(Request $request,Reservation $reservation)
    {
        /**perform check on the time and then cancel a reservation if not confirmed
        $reservations = DB::table('reservations')
            ->where('reservation_status', '=', 'booked')
            ->where('time_of_reservation', '<',Carbon::parse('-48 hours'))
            ->get();

        foreach ($reservations as $reserved)
        {
            if($reserved->reservation_status != "confirmed")
            {
                $reservation_id = $reserved->id;

                DB::table('reservations')
                    ->where('id', $reservation_id)
                    ->update([ 'reservation_status' => 'cancelled']);
            }
        }*/

        //free table if the reservation time is over
        $restaurant_id = $reservation->restaurant_id;
        
        
        $date = date('Y-m-d');
        $all_reservations = DB::table('reservations')
            ->join('table__to__reservations', 'table__to__reservations.reservation_code', '=', 'reservations.reservation_code')
            ->join('tables', 'tables.id', '=', 'table__to__reservations.table_id')
            ->select('reservations.reservation_code', 'reservations.reservation_duration', 'reservations.time_of_reservation')
            ->where('reservations.restaurant_id', $restaurant_id)
            ->where('reservations.date_of_reservation', $date)
            ->get();

        foreach ($all_reservations as $single_reservation){

            $reservation_duration = $single_reservation->reservation_duration;
            $reserved_time = Carbon::parse($single_reservation->time_of_reservation)->format('H:i:s');
            $now = date('H:i:s');
            $current_time = Carbon::parse($now)->format('H:i:s');
            $reserved_hours = Carbon::createFromFormat('H:i:s',$reserved_time)->addMinutes($reservation_duration);
            $reserved_hours = Carbon::parse($reserved_hours)->format('H:i:s');

            //checking if the total reservation time is over
            if($reserved_hours < $current_time)
            {
                $code = $single_reservation->reservation_code;
                $reserved_tables = DB::table('table__to__reservations')
                    ->where('reservation_code', $code)->get();
                foreach ($reserved_tables as $reserved_table)
                {
                    $tables = DB::table('tables')
                        ->where('id', $reserved_table->table_id)
                        ->update([ 'table_is_booked' => 'no']);
                }
            }

        }

        if(Auth::check()){
            
            $restaurant = DB::table('restaurants')
                        ->where('id', $reservation->restaurant_id)
                        ->first();
                        
            $rest_type = $restaurant->Reservation_update_type;
            
            if($request->reservation_attachment != "")
            {
                $request->validate([
                    'reservation_attachment' => 'file|mimes:pdf,csv,jpeg,png,jpg|max:2048'
                ]);

                $reservation_attachment = $request->user_name.time().'.'.$request->reservation_attachment->extension();

                $request->reservation_attachment->move(public_path('images/attachments'), 
                    $reservation_attachment);
            }
            else
            {
                $reservation_attachment = "";
            }
            
            if($reservation->reservation_status == 'Completed')
            {
                return redirect()->back()->with('error', 'you cannot update a completed reservation.');
            }
            elseif($request->reservation_status == 'Completed')
            {
                $code = $reservation->reservation_code;
                $reserved_tables = DB::table('table__to__reservations')
                    ->where('reservation_code', $code)->get();
                foreach ($reserved_tables as $reserved_table)
                {
                    $tables = DB::table('tables')
                        ->where('id', $reserved_table->table_id)
                        ->update([ 'table_is_booked' => 'no']);
                }
            }
            
                
            if(Auth::user()->user_type == "Restaurant" || Auth::user()->user_type == "Manager" || Auth::user()->user_type == "Booking_Manager")
            {
                
            if($rest_type == "Automatic" && $request->reservation_status != 'Completed'){
                 $data = $request->validate([
                    'reservation_status' => 'required|string',
                    'reservation_duration' => 'required|int',
                    'number_of_people' => 'required',
                    'created_by' => 'required|int',
                    'creater_name' => 'required|string'
                ]);  
                
                $reserved_date = $reservation->date_of_reservation;
                $reserved_time = $reservation->time_of_reservation;
                $today = date('Y-m-d');
                $now_time = Carbon::now()->format('H:i:s');

                    if($reserved_date == $today && $reserved_time < $now_time){
                        return redirect()->back()
                            ->with('error', 'You cannot reserve a past time.');
                    }
                    
                    $day_name = Carbon::parse($reserved_date)->dayOfWeek;

                    if($restaurant->sunday_open == 'no' && $day_name == 0)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Sundays');
                    }
                    elseif($restaurant->monday_open == 'no' && $day_name == 1)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Mondays');
                    }
                    elseif($restaurant->tuesday_open == 'no' && $day_name == 2)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Tuesdays');
                    }
                    elseif($restaurant->wednesday_open == 'no' && $day_name == 3)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Wednesdays');
                    }
                    elseif($restaurant->thursday_open == 'no' && $day_name == 4)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Thursday');
                    }
                    elseif($restaurant->friday_open == 'no' && $day_name == 5)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Fridays');
                    }
                    elseif($restaurant->saturday_open == 'no' && $day_name == 6)
                    {
                        return redirect()->back()
                            ->with('error', 'Restaurant is Closed on Saturdays');
                    }
                
                }elseif($rest_type == "Manual" && $request->reservation_status == 'Completed')
                {
                    $data = $request->validate([
                    'reservation_status' => 'required|string',
                    'reservation_total_cost' => 'required|string'
                    ]);
                }

                if($reservation->reservation_status == 'cancelled')
                {
                    return redirect()->back()->with('error', 'you cannot update a cancelled reservation.');
                }
                
                if($reservation->reservation_status == 'Completed')
                {
                    return redirect()->back()->with('error', 'you cannot update a Completed reservation.');
                }

                $reservation_id = $reservation->id;
                $people = intval($request->number_of_people);

                $restaurant = DB::table('restaurants')
                    ->where('id', $restaurant_id)
                    ->first();
                    
                $rest_type = $restaurant->Reservation_update_type;
                
                $reservation_date = $reservation->date_of_reservation;
                $after_date = date('Y-m-d', strtotime($reservation->date_of_reservation. ' + 2 days'));
                $today = date('Y-m-d', strtotime(now()));
                $now = date('Y-m-d H:i:s', strtotime(now()));
                $duration = $reservation->reservation_duration;
                $total_hour = date('H:i:s', strtotime($reservation->time_of_reservation. ' + '.$duration.' minutes'));
                $confirmed_date = date('Y-m-d H:i:s', strtotime($reservation_date.$total_hour));
                
                if($rest_type == 'Automatic')
                {
                    
                    if($rest_type = 'Automatic' && $today >= $after_date)
                    {
                        return redirect()->back()->with('error', 'you cannot update a reservation after 48 hours.');
                    }
                    
                    elseif ($rest_type = 'Automatic' && $reservation->reservation_status == 'confirmed' && $now > $confirmed_date)
                    {
                        return redirect()->back()->with('error', 'you cannot update a reservation after it is completed.');
                    }
                    
                }
                else{
                    
                    $identifier = $reservation->reservation_code;

                    if($request->table_id == "" && $request->reservation_status == "confirmed")
                    {
                        return redirect()->back()->with('error', 'you cannot confirm a reservation without assigning a table.');
                    }

                if($request->table_id)
                {
                    $date = $reservation->date_of_reservation;
                    $time = $reservation->time_of_reservation;

                    $table_ids = $request->table_id;
                    $table_id_count = count($table_ids);

                    $table_max_cover = 0;
                    $table_min_cover = 0;
                    $no_of_people = $request->number_of_people;
                    $table_total = 0;

                    for($i = 0; $i < $table_id_count; $i++)
                    {
                        $table_id = $table_ids[$i];
                        $table = DB::table('tables')->where('id', $table_id)->first();
                        $table_price = intval($table->table_price);
                        $table_total = $table_total + $table_price;

                        $max_cover = $table->max_covers;
                        $min_cover = $table->min_covers;
                        $table_max_cover = $table_max_cover + $max_cover;
                        $table_min_cover = $table_min_cover + $min_cover;

                        $get_table = DB::table('reservations')
                            ->join('table__to__reservations', 'table__to__reservations.reservation_code', '=', 'reservations.reservation_code')
                            ->join('tables', 'tables.id', '=', 'table__to__reservations.table_id')
                            ->where('table__to__reservations.table_id', $table_id)
                            ->where('reservations.date_of_reservation', '=', $date)
                            ->where('reservations.time_of_reservation', '=', $time)
                            ->where('reservations.reservation_status', 'confirmed')
                            ->first();

                        if(!is_null($get_table) && $reservation->reservation_status == "booked")
                        {
                            $table_reserved_for = $get_table->time_of_reservation;
                            $new_reservation = $request->time_of_reservation;
                            $table_duration = $get_table->reservation_duration;
                            $total_hour = Carbon::createFromFormat('H:i:s',$table_reserved_for)->addMinutes($table_duration);
                            $total_hour = Carbon::parse($total_hour)->format('H:i:s');

                            if($new_reservation < $total_hour)
                            {
                                return redirect()->back()
                                    ->with('error', 'Reservation not created. You cannot book a table for two different reservations at the same time and date or before the current reservation ends.');
                            }
                        }
                    }

                    if($rest_type == 'Manual')
                    {
                        $total_price = $request->reservation_total_cost;
                        $created_by = $reservation->created_by;
                        $creater_name = $reservation->creater_name;
                    }
                    else{
                        $total_price = $table_total * $people;
                        $created_by = Auth::user()->id;
                        $creater_name = Auth::user()->name;
                    }

                    if( $no_of_people > $table_max_cover)
                    {
                        return redirect()->back()->with('error', 'Table capacity is less than the number of people selected.');
                    }
                    elseif ($no_of_people < $table_min_cover)
                    {
                        return redirect()->back()->with('error', 'Table capacity is not utilised properly.');
                    }

                    $closing_hour = $restaurant->restaurant_closing_hour;
                    $opening_hour = $restaurant->restaurant_opening_hour;
                    $restaurant_max_capacity = $restaurant->Restaurant_max_capacity;
                    $Restaurant_email = $restaurant->Restaurant_email;

                    $booked_seats = DB::table('reservations')
                        ->join('table__to__reservations', 'table__to__reservations.reservation_code',
                            '=', 'reservations.reservation_code')
                        ->where('reservations.restaurant_id', $restaurant_id)
                        ->where('reservations.reservation_status', 'confirmed')
                        ->where('reservations.date_of_reservation', 'LIKE',$date)
                        ->where('reservations.time_of_reservation', 'LIKE',$time)
                        ->sum('reservations.number_of_people');

                    $available_seats = $restaurant_max_capacity - $booked_seats;

                    

                    /**$check_table = DB::table('reservations')
                    ->where('table_id', $request->table_id)
                    ->where('date_of_reservation', 'LIKE' , $date)
                    ->where('time_of_reservation', 'LIKE', $time)
                    ->get();*/

                   
                    
                    if($request->reservation_status != 'Completed')
                    {
                        $check_table = DB::table('reservations')
                        ->join('table__to__reservations', 'table__to__reservations.reservation_code',
                            '=', 'reservations.reservation_code')
                        ->join('tables', 'tables.id', '=', 'table__to__reservations.table_id')
                        ->where('table__to__reservations.table_id', $request->table_id)
                        ->where('reservations.date_of_reservation','LIKE', $date)
                        ->where('reservations.time_of_reservation','LIKE', $time)
                        ->where('tables.table_is_booked', 'yes')
                        ->get();
                        
                        /**$check_table = DB::table('reservations')
                        ->join('table__to__reservations', 'table__to__reservations.reservation_code',
                            '=', 'reservations.reservation_code')
                        ->where('table__to__reservations.table_id', $request->table_id)
                        ->where('reservations.date_of_reservation', 'LIKE',$date)
                        ->where('reservations.time_of_reservation', 'LIKE',$time)
                        ->where('reservations.reservation_status', 'confirmed')
                        ->get();*/
                        
                         $count_check_table = count($check_table);
                         
                         if($count_check_table > 0)
                        {
                            return redirect()->back()->with('error', "'Table is booked at ' + $time + 'try other available times.'");
                        }
                        
                    }

                    if($time < $opening_hour || $time > $closing_hour)
                    {
                        return redirect()->back()->with('error', 'Restaurant is Closed at the specified hour.');
                    }
                    elseif ($no_of_people > $available_seats)
                    {
                        return redirect()->back()->with('error', 'Number of People Exceeds the Available Seats of Your Restaurant.');
                    }
                    elseif ($no_of_people > $restaurant_max_capacity)
                    {
                        return redirect()->back()->with('error', 'Number of People Exceeds the Capacity of Your Restaurant.');
                    }
                    elseif ($people < $table->min_covers && $people > $table->max_covers)
                    {
                        return redirect()->back()->with('error', 'Table capacity is not utilized well.');
                    }
                    else{
                        $booked_table = DB::table('table__to__reservations')
                            ->select('table_id')
                            ->where('reservation_code', $identifier)->get();
                        foreach ($booked_table as $booked)
                        {
                            $table_id = $booked->table_id;
                            DB::table('tables')
                                ->where('id', $table_id)
                                ->update([
                                    'table_is_booked' => 'no'
                                ]);
                        }

                        $new_code = Str::random(10);

                        for($i = 0; $i < $table_id_count; $i++)
                        {
                            $table_id = $table_ids[$i];
                            $table_to_reservation = new Table_To_Reservation;
                            $table_to_reservation->reservation_code = $new_code;
                            $table_to_reservation->table_id = $table_id;
                            $table_to_reservation->save();

                            //update table status on the tables table on DB
                            DB::table('tables')
                                ->where('id', $table_id)
                                ->update([
                                    'table_is_booked' => 'yes'
                                ]);
                        }

                    }

                    $update = DB::table('reservations')
                        ->where('id', $reservation_id)
                        ->update([
                            'table_id' => $identifier,
                            'table_indentifier' => $identifier,
                            'reservation_status' => $request->reservation_status,
                            'reservation_duration' => $request->reservation_duration,
                            'number_of_people' => $people,
                            'reservation_total_cost' => $total_price,
                            'reservation_code' => $new_code,
                            'hostess_note' => $request->hostess_note,
                            'created_by' => $created_by,
                            'creater_name' => $creater_name,
                            'reserver_message' => $request->reserver_message,
                            'reservation_attachment' => $reservation_attachment,
                            'user_name' => $request->user_name,
                            'user_email' => $request->user_email,
                            'user_phone' => $request->user_phone,
                        ]);
                        
                    if($request->reservation_status == "Completed" || $request->reservation_status == "cancelled")
                    {
                        $booked_table = DB::table('table__to__reservations')
                            ->select('table_id')
                            ->where('reservation_code', $identifier)->get();
                        foreach ($booked_table as $booked)
                        {
                            $table_id = $booked->table_id;
                            DB::table('tables')
                                ->where('id', $table_id)
                                ->update([
                                    'table_is_booked' => 'no'
                                ]);
                        }
                    }
                    
                    if($update)
                    {
                        if($request->reservation_status == 'booked' || $request->reservation_status == 'confirmed')
                        {
                            $restaurant_booking_managers = DB::table('restaurant__users')
                            ->join('users', 'users.id', '=', 'restaurant__users.user_id')
                            ->where('restaurant__users.restaurant_id', $restaurant_id)
                            ->where('restaurant__users.user_role', 'Booking_Manager')
                            ->get();
                            
                        foreach($restaurant_booking_managers as $booking_manager)
                        {
                            $booking_manager_email = $booking_manager->email;
                            
                            $data = [
                                'subject' => 'Reservation Update.',
                                'email' => $reservation->user_email,
                                'status' => $request->reservation_status,
                                'date' => $reservation->date_of_reservation,
                                'time' => $reservation->time_of_reservation,
                                'guest_name' => $reservation->user_name,
                                'reservation_code' => $new_code,
                                'Restaurant_name' => $restaurant->Restaurant_name,
                                'no_of_people' => $people,
                                'restaurant_id' => $reservation->restaurant_id,
                                'Restaurant_email' => $Restaurant_email,
                                'booking_manager_email' => $booking_manager_email,
                                'Additional_note' => $reservation->reserver_message
                            ];
                            
                            Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                                $message->to($data['booking_manager_email'])
                                    ->subject($data['subject']);
                            });
                        }
                
                        $restaurant_managers = DB::table('restaurant__users')
                            ->join('users', 'users.id', '=', 'restaurant__users.user_id')
                            ->where('restaurant__users.restaurant_id', $restaurant_id)
                            ->Where('restaurant__users.user_role', 'Manager')
                            ->get();

                        foreach($restaurant_managers as $manager)
                        {
                            $manager_email = $manager->email;
                            
                            $data = [
                                'subject' => 'Reservation Update.',
                                'email' => $reservation->user_email,
                                'status' => $request->reservation_status,
                                'date' => $reservation->date_of_reservation,
                                'time' => $reservation->time_of_reservation,
                                'guest_name' => $reservation->user_name,
                                'reservation_code' => $new_code,
                                'Restaurant_name' => $restaurant->Restaurant_name,
                                'no_of_people' => $people,
                                'restaurant_id' => $reservation->restaurant_id,
                                'Restaurant_email' => $Restaurant_email,
                                'manager_email' => $manager_email,
                                'Additional_note' => $reservation->reserver_message
                            ];
                            
                            Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                                $message->to($data['manager_email'])
                                    ->subject($data['subject']);
                            });
                        }
                        
                        $data = [
                                'subject' => 'Reservation Update.',
                                'email' => $reservation->user_email,
                                'status' => $request->reservation_status,
                                'date' => $reservation->date_of_reservation,
                                'time' => $reservation->time_of_reservation,
                                'guest_name' => $reservation->user_name,
                                'reservation_code' => $new_code,
                                'Restaurant_name' => $restaurant->Restaurant_name,
                                'no_of_people' => $people,
                                'restaurant_id' => $reservation->restaurant_id,
                                'Restaurant_email' => $Restaurant_email,
                                'Additional_note' => $reservation->reserver_message
                            ];
                    
                            Mail::send('Emails.bookedEmail', $data, function($message) use ($data) {
                                $message->to($data['email'])
                                    ->subject($data['subject']);
                            });

                            if (Mail::failures()) {
                                return redirect()->back()->with('success', 'Reservation Updated Successfully. But Email not sent.');
                            }else{
                                return redirect()->back()->with('success', 'Reservation Updated Successfully. Email sent Successfully.');
                            }
                        }
                        else{
                            return redirect()->back()->with('success', 'Reservation Updated Successfully.');
                        }
                        

                    }
                    else{
                        return redirect()->back()->with('error', 'reservation not updated check your inputs.');
                    }
                }
                else{
                    $table_price = "0";
                    
                    $table_ids = "";
                    $table_id_count = "0";
                    $Restaurant_email = $restaurant->Restaurant_email;
                    
                    if($rest_type == 'Manual')
                    {
                        $total_price = $request->reservation_total_cost;
                        $created_by = Auth::user()->id;
                        $creater_name = Auth::user()->name;
                    }
                    else{
                        $total_price = "0";
                        $created_by = Auth::user()->id;
                        $creater_name = Auth::user()->name;
                    }

                    $update = DB::table('reservations')
                        ->where('id', $reservation_id)
                        ->update([
                            'table_id' => $identifier,
                            'table_indentifier' => $identifier,
                            'reservation_status' => $request->reservation_status,
                            'reservation_duration' => $request->reservation_duration,
                            'number_of_people' => $people,
                            'reservation_total_cost' => $total_price,
                            'hostess_note' => $request->hostess_note,
                            'created_by' => $created_by,
                            'creater_name' => $creater_name,
                            'reserver_message' => $request->reserver_message,
                            'reservation_attachment' => $reservation_attachment
                        ]);
                        
                    if($request->reservation_status == "Completed" || $request->reservation_status == "cancelled")
                    {
                        $booked_table = DB::table('table__to__reservations')
                            ->select('table_id')
                            ->where('reservation_code', $identifier)->get();
                        foreach ($booked_table as $booked)
                        {
                            $table_id = $booked->table_id;
                            DB::table('tables')
                                ->where('id', $table_id)
                                ->update([
                                    'table_is_booked' => 'no'
                                ]);
                        }
                    }
                    
                    if($update)
                    {
                        if($request->reservation_status == 'booked' || $request->reservation_status == 'confirmed')
                        {
                            $restaurant_booking_managers = DB::table('restaurant__users')
                            ->join('users', 'users.id', '=', 'restaurant__users.user_id')
                            ->where('restaurant__users.restaurant_id', $restaurant_id)
                            ->where('restaurant__users.user_role', 'Booking_Manager')
                            ->get();
                            
                        foreach($restaurant_booking_managers as $booking_manager)
                        {
                            $booking_manager_email = $booking_manager->email;
                            
                            $data = [
                                'subject' => 'Reservation Update.',
                                'email' => $reservation->user_email,
                                'status' => $request->reservation_status,
                                'date' => $reservation->date_of_reservation,
                                'time' => $reservation->time_of_reservation,
                                'guest_name' => $reservation->user_name,
                                'reservation_code' => $reservation->reservation_code,
                                'Restaurant_name' => $restaurant->Restaurant_name,
                                'no_of_people' => $people,
                                'restaurant_id' => $reservation->restaurant_id,
                                'Restaurant_email' => $Restaurant_email,
                                'booking_manager_email' => $booking_manager_email,
                                'Additional_note' => $reservation->reserver_message
                            ];
                            
                            Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                                $message->to($data['booking_manager_email'])
                                    ->subject($data['subject']);
                            });
                        }
                
                        $restaurant_managers = DB::table('restaurant__users')
                            ->join('users', 'users.id', '=', 'restaurant__users.user_id')
                            ->where('restaurant__users.restaurant_id', $restaurant_id)
                            ->Where('restaurant__users.user_role', 'Manager')
                            ->get();

                        foreach($restaurant_managers as $manager)
                        {
                            $manager_email = $manager->email;
                            
                            $data = [
                                'subject' => 'Reservation Update.',
                                'email' => $reservation->user_email,
                                'status' => $request->reservation_status,
                                'date' => $reservation->date_of_reservation,
                                'time' => $reservation->time_of_reservation,
                                'guest_name' => $reservation->user_name,
                                'reservation_code' => $reservation->reservation_code,
                                'Restaurant_name' => $restaurant->Restaurant_name,
                                'no_of_people' => $people,
                                'restaurant_id' => $reservation->restaurant_id,
                                'Restaurant_email' => $Restaurant_email,
                                'manager_email' => $manager_email,
                                'Additional_note' => $reservation->reserver_message
                            ];
                            
                            Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                                $message->to($data['manager_email'])
                                    ->subject($data['subject']);
                            });
                        }
                        
                        $data = [
                                'subject' => 'Reservation Update.',
                                'email' => $reservation->user_email,
                                'status' => $request->reservation_status,
                                'date' => $reservation->date_of_reservation,
                                'time' => $reservation->time_of_reservation,
                                'guest_name' => $reservation->user_name,
                                'reservation_code' => $reservation->reservation_code,
                                'Restaurant_name' => $restaurant->Restaurant_name,
                                'no_of_people' => $people,
                                'restaurant_id' => $reservation->restaurant_id,
                                'Restaurant_email' => $Restaurant_email,
                                'Additional_note' => $reservation->reserver_message
                            ];
                    
                        Mail::send('Emails.bookedEmail', $data, function($message) use ($data) {
                            $message->to($data['email'])
                                ->subject($data['subject']);
                        });

                        if (Mail::failures()) {
                            return redirect()->back()->with('success', 'Reservation Updated Successfully. But Email not sent.');
                        }else{
                            return redirect()->back()->with('success', 'Reservation Updated Successfully. Email sent Successfully.');
                        }
                        }
                        else{
                            return redirect()->back()->with('success', 'Reservation Updated Successfully.');
                        }

                    }
                    else{
                        return redirect()->back()->with('error', 'reservation not updated check your inputs.');
                    }
                }
                
                
                }

            }
            else{
                return redirect()->back()
                    ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
            }
        }
        else{
            return redirect()->route('login');
        }
    }

    public function update_reservation_status(Request $request)
    {
        /**perform check on the time and then cancel a reservation if not confirmed
        $reservations = DB::table('reservations')
            ->where('reservation_status', '=', 'booked')
            ->where('time_of_reservation', '<',Carbon::parse('-48 hours'))
            ->get();

        foreach ($reservations as $reservation)
        {
            if($reservation->reservation_status != "confirmed")
            {
                $reservation_id = $reservation->id;

                DB::table('reservations')
                    ->where('id', $reservation_id)
                    ->update([ 'reservation_status' => 'cancelled']);
            }
        }*/
                
        $request->validate([
            'reservation_id' => 'required',
            'reservation_status' => 'required'
        ]);

        //free table if the reservation time is over
        $restaurant_id = $request->restaurant_id;
        $date = date('Y-m-d');
        $all_reservations = DB::table('reservations')
            ->join('table__to__reservations', 'table__to__reservations.reservation_code', '=', 'reservations.reservation_code')
            ->join('tables', 'tables.id', '=', 'table__to__reservations.table_id')
            ->select('reservations.reservation_code', 'reservations.reservation_duration', 'reservations.time_of_reservation')
            ->where('reservations.restaurant_id', $restaurant_id)
            ->where('reservations.date_of_reservation', $date)
            ->get();

        foreach ($all_reservations as $single_reservation){

            $reservation_duration = $single_reservation->reservation_duration;
            $reserved_time = Carbon::parse($single_reservation->time_of_reservation)->format('H:i:s');
            $now = date('H:i:s');
            $current_time = Carbon::parse($now)->format('H:i:s');
            $reserved_hours = Carbon::createFromFormat('H:i:s',$reserved_time)->addMinutes($reservation_duration);
            $reserved_hours = Carbon::parse($reserved_hours)->format('H:i:s');

            //checking if the total reservation time is over
            if($reserved_hours < $current_time)
            {
                $code = $single_reservation->reservation_code;
                $reserved_tables = DB::table('table__to__reservations')
                    ->where('reservation_code', $code)->get();
                foreach ($reserved_tables as $reserved_table)
                {
                    $tables = DB::table('tables')
                        ->where('id', $reserved_table->table_id)
                        ->update([ 'table_is_booked' => 'no']);
                }
            }

        }

        $reservation = DB::table('reservations')
            ->where('id', $request->reservation_id)
            ->first();
        $rest_id = $reservation->restaurant_id;
        $restaurant = DB::table('restaurants')
            ->where('id', $rest_id)
            ->first();

        $Restaurant_email = $restaurant->Restaurant_email;

        if(Auth::user()->user_type == "Restaurant" || Auth::user()->user_type == "Manager" || Auth::user()->user_type == "Booking_Manager")
        {
            $after_date = date('Y-m-d', strtotime($reservation->date_of_reservation. ' + 2 days'));
            $today = date('Y-m-d', strtotime(now()));
            $now = date('Y-m-d H:i:s', strtotime(now()));
            $duration = $reservation->reservation_duration;
            $total_hour = date('H:i:s', strtotime($reservation->time_of_reservation. ' + '.$duration.' minutes'));
            $confirmed_date = date('Y-m-d H:i:s', strtotime($reservation->date_of_reservation.$total_hour));

            if($reservation->reservation_status == 'cancelled')
            {
                return redirect()->back()->with('error', 'you cannot update a cancelled reservation.');
            }
            elseif ($reservation->reservation_status == 'confirmed' && $now > $confirmed_date)
            {
                return redirect()->back()->with('error', 'you cannot update a reservation after it is completed.');
            }
            elseif($after_date >= $today)
            {
                return redirect()->back()->with('error', 'you cannot update a created reservation after two days.');
            }
            else{
                    DB::table('reservations')
                        ->where('id', $request->reservation_id)
                        ->update([
                            'reservation_status' => $request->reservation_status
                        ]);
                        
                    if($request->reservation_status == "Completed" || $request->reservation_status == "cancelled")
                    {
                        $booked_table = DB::table('table__to__reservations')
                            ->select('table_id')
                            ->where('reservation_code', $reservation->reservation_code)->get();
                            
                        foreach ($booked_table as $booked)
                        {
                            $table_id = $booked->table_id;
                            DB::table('tables')
                                ->where('id', $table_id)
                                ->update([
                                    'table_is_booked' => 'no'
                                ]);
                        }
                    }
                    
                    if($request->reservation_status == "cancelled"){
                        $restaurant_booking_managers = DB::table('restaurant__users')
                            ->join('users', 'users.id', '=', 'restaurant__users.user_id')
                            ->where('restaurant__users.restaurant_id', $restaurant_id)
                            ->where('restaurant__users.user_role', 'Booking_Manager')
                            ->get();
                            
                        foreach($restaurant_booking_managers as $booking_manager)
                        {
                            $booking_manager_email = $booking_manager->email;
                            
                            $data = [
                                'subject' => 'Reservation Update.',
                                'email' => $reservation->user_email,
                                'status' => $request->reservation_status,
                                'date' => $reservation->date_of_reservation,
                                'time' => $reservation->time_of_reservation,
                                'guest_name' => $reservation->user_name,
                                'reservation_code' => $reservation->reservation_code,
                                'Restaurant_name' => $restaurant->Restaurant_name,
                                'no_of_people' => $reservation->number_of_people,
                                'restaurant_id' => $reservation->restaurant_id,
                                'Restaurant_email' => $Restaurant_email,
                                'booking_manager_email' => $booking_manager_email,
                                'Additional_note' => $reservation->reserver_message
                            ];
                            
                            Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                                $message->to($data['booking_manager_email'])
                                    ->subject($data['subject']);
                            });
                        }
                
                        $restaurant_managers = DB::table('restaurant__users')
                            ->join('users', 'users.id', '=', 'restaurant__users.user_id')
                            ->where('restaurant__users.restaurant_id', $restaurant_id)
                            ->Where('restaurant__users.user_role', 'Manager')
                            ->get();

                        foreach($restaurant_managers as $manager)
                        {
                            $manager_email = $manager->email;
                            
                            $data = [
                                'subject' => 'Reservation Update.',
                                'email' => $reservation->user_email,
                                'status' => $request->reservation_status,
                                'date' => $reservation->date_of_reservation,
                                'time' => $reservation->time_of_reservation,
                                'guest_name' => $reservation->user_name,
                                'reservation_code' => $reservation->reservation_code,
                                'Restaurant_name' => $restaurant->Restaurant_name,
                                'no_of_people' => $reservation->number_of_people,
                                'restaurant_id' => $reservation->restaurant_id,
                                'Restaurant_email' => $Restaurant_email,
                                'manager_email' => $manager_email,
                                'Additional_note' => $reservation->reserver_message
                            ];
                            
                            Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
                                $message->to($data['manager_email'])
                                    ->subject($data['subject']);
                            });
                        }
                        
                        $data = [
                                'subject' => 'Reservation Update.',
                                'email' => $reservation->user_email,
                                'status' => $request->reservation_status,
                                'date' => $reservation->date_of_reservation,
                                'time' => $reservation->time_of_reservation,
                                'guest_name' => $reservation->user_name,
                                'reservation_code' => $reservation->reservation_code,
                                'Restaurant_name' => $restaurant->Restaurant_name,
                                'no_of_people' => $reservation->number_of_people,
                                'restaurant_id' => $reservation->restaurant_id,
                                'Restaurant_email' => $Restaurant_email,
                                'Additional_note' => $reservation->reserver_message
                            ];
                    
                        Mail::send('Emails.bookedEmail', $data, function($message) use ($data) {
                            $message->to($data['email'])
                                ->subject($data['subject']);
                        });
    
                        if (Mail::failures()) {
                            return redirect()->back()
                                ->with('success', 'Reservation Status Updated Successfully. But Email not sent.');
                        }else{
                            return redirect()->back()
                                ->with('success', 'Reservation Status Updated Successfully. Email sent Successfully');
                        }
                    }
                    else{
                        return redirect()->back()
                            ->with('success', 'Reservation Status Updated Successfully.');
                    }
                    
                    

            }
        }
        else{
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }

    }

    public function destroy(Reservation $reservation)
    {
        if(Auth::check() && Auth::user()->user_type == "Restaurant"){
            $reservation_code = $reservation->reservation_code;

            DB::table('table__to__reservations')
                ->where('reservation_code', $reservation_code)
                ->delete();

            $reservation->delete();

            $reserved_tables = DB::table('table__to__reservations')
                ->where('reservation_code', $reservation_code)->get();
            foreach ($reserved_tables as $reserved_table)
            {
                DB::table('tables')
                    ->where('id', $reserved_table->table_id)
                    ->update([ 'table_is_booked' => 'no']);
            }

            return redirect()->back()
                ->with('success', 'Reservation deleted Successfully.');
        }
        else{
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }
    }
}
