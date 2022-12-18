<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Table;
use App\Models\Restaurant;
use App\Models\Reservation;

class TableController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
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

        $tables = DB::table('tables')
                  ->where('restaurant_id', $restaurant_id)
                  ->get();

        return view('Admin.Table.index', compact('tables', 'restaurant_id', 'restaurants', 'restaurant'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_table($id)
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

        $tables = DB::table('tables')
            ->where('restaurant_id', $restaurant_id)
            ->get();

        $restaurant = DB::table('restaurants')
                      ->where('id', $restaurant_id)
                      ->first();

        $rest_type = $restaurant->Reservation_update_type;

        $restaurant_name = $restaurant->Restaurant_name;

        return view('Admin.Table.create', compact('tables', 'restaurant_id', 'restaurant', 'restaurant_name',
            'restaurants', 'rest_type'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function get_history(Request $request)
    {
        if($request->id && $request->rest_id != "")
        {
            $request->validate([
                'id' => 'required',
                'rest_id' => 'required',
                'date' => 'required'
            ]);

            $table_id = $request->id;
            $rest_id = $request->rest_id;
            $date = $request->date;

            $table_history = DB::table('table__to__reservations')
                ->join('reservations', 'table__to__reservations.reservation_code', '=', 'reservations.reservation_code')
                ->join('tables', 'table__to__reservations.table_id', '=', 'tables.id')
                ->select('reservations.user_name', 'reservations.date_of_reservation', 'reservations.time_of_reservation',
                    'reservations.number_of_people', 'reservations.reservation_total_cost', 'reservations.reservation_code',
                    'reservations.reservation_status', 'reservations.reservation_type', 'reservations.reservation_tag')
                ->where('reservations.restaurant_id', $rest_id)
                ->where('reservations.date_of_reservation', $date)
                ->where('table__to__reservations.table_id', $table_id)
                ->distinct()->get('table__to__reservations.reservation_code');

            echo json_encode($table_history);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'table_name' => 'required|string',
            'min_covers' => 'required|int',
            'max_covers' => 'required|int',
            'restaurant_id' => 'required|int',
            'table_shape' => 'required'
        ]);

        if(Auth::user()->user_type == "Restaurant" || Auth::user()->user_type == "Manager")
        {
            if($request->min_covers == 0 || $request->max_covers == 0)
            {
                return redirect()->back()->with('error', "Table cover value cannot be zero.");
            }

            $restaurant = DB::table('restaurants')->where('id', $request->restaurant_id)->first();
            $rest_type = $restaurant->Reservation_update_type;

            if($rest_type == 'Manual')
            {
                $max_capacity = $restaurant->Restaurant_max_capacity;
                $tables = DB::table('tables')
                    ->where('restaurant_id', $request->restaurant_id)
                    ->sum('max_covers');
                $max_cover = $request->max_covers;
                $table_shape = $request->table_shape;

                $total = $tables + $max_cover;
                if($total > $max_capacity)
                {
                    return redirect()->back()->with('error', 'Table max cover exceeds the total restaurant capacity');
                }
                else{
                    if($request->table_status)
                    {
                        $table_status = 'active';
                    }
                    else{
                        $table_status = 'inactive';
                    }

                    $info = new Table;

                    $info->table_name = $request->table_name;
                    //$info->table_price = $request->table_price;
                    $info->min_covers = $request->min_covers;
                    $info->max_covers = $request->max_covers;
                    $info->restaurant_id = $request->restaurant_id;
                    $info->table_status = $table_status;
                    $info->table_shape = $table_shape;

                    $info->save();

                    return redirect()->back()->with('success', 'Table Created Successfully.');
                }
            }
            else{
                $request->validate([
                    'table_price' => 'required|int',
                ]);

                $max_capacity = $restaurant->Restaurant_max_capacity;
                $tables = DB::table('tables')
                    ->where('restaurant_id', $request->restaurant_id)
                    ->sum('max_covers');
                $max_cover = $request->max_covers;
                $table_shape = $request->table_shape;

                $total = $tables + $max_cover;
                if($total > $max_capacity)
                {
                    return redirect()->back()->with('error', 'Table max cover exceeds the total restaurant capacity');
                }
                else{
                    if($request->table_status)
                    {
                        $table_status = 'active';
                    }
                    else{
                        $table_status = 'inactive';
                    }

                    $info = new Table;

                    $info->table_name = $request->table_name;
                    $info->table_price = $request->table_price;
                    $info->min_covers = $request->min_covers;
                    $info->max_covers = $request->max_covers;
                    $info->restaurant_id = $request->restaurant_id;
                    $info->table_status = $table_status;
                    $info->table_shape = $table_shape;

                    $info->save();

                    return redirect()->back()->with('success', 'Table Created Successfully.');
                }
            }

        }
        else{
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function show(Table $table)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function edit_table_layout($id)
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
            ->where('id', $restaurant_id)
            ->first();

        $rest_type = $restaurant->Reservation_update_type;

        $tables = DB::table('tables')
            ->where('restaurant_id', $restaurant_id)
            ->get();

        return view('Admin.Table.edit', compact( 'tables', 'restaurant_id', 'restaurants',
            'restaurant', 'rest_type'));
    }

    public function get_table_positions($id)
    {
        $restaurant_id = $id;
        $date = date('Y-m-d');
        $all_reservations = DB::table('reservations')
            ->join('table__to__reservations', 'table__to__reservations.reservation_code', '=', 'reservations.reservation_code')
            ->join('tables', 'tables.id', '=', 'table__to__reservations.table_id')
            ->select('reservations.reservation_code', 'reservations.reservation_duration', 'reservations.time_of_reservation')
            ->where('reservations.restaurant_id', $restaurant_id)
            ->where('reservations.date_of_reservation', $date)
            ->where('tables.table_is_booked', '=', 'yes')
            ->distinct()->get('reservation_code');

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

        $tables = DB::table('tables')
            ->where('restaurant_id', $restaurant_id)
            ->get();

        echo json_encode($tables);

    }

    public function booked_tables($id)
    {
        $reservation = DB::table('reservations')->where('id', $id)->first();
        $reservation_code = $reservation->reservation_code;
        $date = $reservation->date_of_reservation;

        $booked_tables = DB::table('tables')
            ->join('table__to__reservations', 'tables.id', '=', 'table__to__reservations.table_id')
            ->join('reservations', 'reservations.reservation_code', '=', 'table__to__reservations.reservation_code')
            ->select('tables.id', 'tables.table_name', 'tables.table_is_booked', 'table__to__reservations.reservation_code', 'reservations.time_of_reservation')
            ->where('table__to__reservations.reservation_code', $reservation_code)
            ->where('reservations.date_of_reservation', $date)
            ->get();

        echo json_encode($booked_tables);
    }

    public function update_table_layout(Request $request)
    {
        if(Auth::user()->user_type == "Restaurant" || Auth::user()->user_type == "Manager") {

            if ($request->positions != "") {
                $id_orders = json_decode($request->positions, true);

                $order = $request->ids;

                $idArray = explode(",", $order);

                $t_positions = array();

                foreach ($id_orders as $orders) {
                    $t_positions[] = 'top: ' . $orders['top'] . ',' . ' left: ' . $orders['left'];
                }

                $count = 0;
                foreach ($idArray as $id) {
                    DB::table('tables')
                        ->where('id', $id)
                        ->update([
                            'table_position' => $t_positions[$count]
                        ]);

                    $count++;
                }
            }
        }
        else{
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }
    }

    //get booked tables and return json data
    public function get_booked_tables()
    {
        $reservations = DB::table('reservations')
            ->where('reservation_status', '=', 'booked')
            ->where('created_at', '<',Carbon::parse('-48 hours'))
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
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Table $table)
    {
        $request->validate([
            'table_name' => 'required|string',
            'min_covers' => 'required|int',
            'max_covers' => 'required|int'
        ]);

        if(Auth::user()->user_type == "Restaurant" || Auth::user()->user_type == "Manager") {

            if($request->min_covers == 0 || $request->max_covers == 0)
            {
                return redirect()->back()->with('error', "Table cover value cannot be zero.");
            }

            $restaurant = DB::table('restaurants')->where('id', $table->restaurant_id)->first();

            $rest_type = $restaurant->Reservation_update_type;

            if($rest_type == 'Manual')
            {
                $max_capacity = $restaurant->Restaurant_max_capacity;
                $tables = DB::table('tables')
                    ->where('restaurant_id', $table->restaurant_id)
                    ->sum('max_covers');
                $table_cover = $tables - $table->max_covers;
                $max_cover = $request->max_covers;

                $total = $table_cover + $max_cover;
                if($total > $max_capacity)
                {
                    return redirect()->back()->with('error', 'Table max cover exceeds the total restaurant capacity');
                }

                $table_id = $table->id;

                if($request->table_status)
                {
                    $table_status = 'active';
                }
                else{
                    $table_status = 'inactive';
                }

                if($request->table_shape){
                    DB::table('tables')
                        ->where('id', $table_id)
                        ->update([
                            'table_name' => $request->table_name,
                            'min_covers' => $request->min_covers,
                            'max_covers' => $request->max_covers,
                            'table_is_booked' => $request->table_is_booked,
                            'table_status' => $table_status,
                            'table_shape' => $request->table_shape
                        ]);
                }
                else{
                    DB::table('tables')
                        ->where('id', $table_id)
                        ->update([
                            'table_name' => $request->table_name,
                            'min_covers' => $request->min_covers,
                            'max_covers' => $request->max_covers,
                            'table_is_booked' => $request->table_is_booked,
                            'table_status' => $table_status
                        ]);
                }

                return redirect()->back()->with('success', 'Table Updated Successfully.');
            }
            else{
                $request->validate([
                    'table_price' => 'required|int',
                ]);

                $max_capacity = $restaurant->Restaurant_max_capacity;
                $tables = DB::table('tables')
                    ->where('restaurant_id', $table->restaurant_id)
                    ->sum('max_covers');
                $table_cover = $tables - $table->max_covers;
                $max_cover = $request->max_covers;

                $total = $table_cover + $max_cover;
                if($total > $max_capacity)
                {
                    return redirect()->back()->with('error', 'Table max cover exceeds the total restaurant capacity');
                }

                $table_id = $table->id;

                if($request->table_status)
                {
                    $table_status = 'active';
                }
                else{
                    $table_status = 'inactive';
                }

                if($request->table_shape){
                    DB::table('tables')
                        ->where('id', $table_id)
                        ->update([
                            'table_name' => $request->table_name,
                            'table_price' => $request->table_price,
                            'min_covers' => $request->min_covers,
                            'max_covers' => $request->max_covers,
                            'table_is_booked' => $request->table_is_booked,
                            'table_status' => $table_status,
                            'table_shape' => $request->table_shape
                        ]);
                }
                else{
                    DB::table('tables')
                        ->where('id', $table_id)
                        ->update([
                            'table_name' => $request->table_name,
                            'table_price' => $request->table_price,
                            'min_covers' => $request->min_covers,
                            'max_covers' => $request->max_covers,
                            'table_is_booked' => $request->table_is_booked,
                            'table_status' => $table_status
                        ]);
                }

                return redirect()->back()->with('success', 'Table Updated Successfully.');
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
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function destroy(Table $table)
    {
        if(Auth::user()->user_type == "Restaurant" || Auth::user()->user_type == "Manager") {
            $id = $table->id;
            $tables = DB::table('tables')
                ->where('id', '=', $id)
                ->where('table_is_booked', '=','yes')
                ->get();

            $count_table = count($tables);
            if($count_table > 0)
            {
                return redirect()->back()
                    ->with('error','Table Not Deleted! you cannot delete a table while it is still booked.');
            }
            else{
                $table->delete();

                return redirect()->back()
                    ->with('success','Table Deleted successfully');
            }
        }
        else{
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }
    }
}
