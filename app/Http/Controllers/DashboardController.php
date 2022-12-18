<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Feedback;
use App\Models\Restaurant;
use App\Models\order;
use App\Models\order_menu;
use App\Models\menu;
use App\Models\Category;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\Guest;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    public function index()
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
                       ->where('user_id', $user_id)
                       ->get();

            //var_dump($user_id);
            //die();
        }
        elseif (Auth::user()->user_type == 'Administrator')
        {
            return redirect(RouteServiceProvider::ADMIN);
        }

    	$online_reservations = DB::table('reservations')
            ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'online')
            ->get();

        $walkin_reservations = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'walkin')
            ->get();
            
        $completed_online_reservations = DB::table('reservations')
            ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'online')
            ->where('reservations.reservation_status', 'Completed')
            ->get();

        $booked_online_reservations = DB::table('reservations')
            ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'online')
            ->where('reservations.reservation_status', 'booked')
            ->get();

        $confirmed_online_reservations = DB::table('reservations')
            ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'online')
            ->where('reservations.reservation_status', 'confirmed')
            ->get();

        $cancelled_online_reservations = DB::table('reservations')
            ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'online')
            ->where('reservations.reservation_status', 'cancelled')
            ->get();
            
        $completed_walkin_reservations = DB::table('reservations')
            ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'walkin')
            ->where('reservations.reservation_status', 'completed')
            ->get();

        $booked_walkin_reservations = DB::table('reservations')
            ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'walkin')
            ->where('reservations.reservation_status', 'booked')
            ->get();

        $confirmed_walkin_reservations = DB::table('reservations')
            ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'walkin')
            ->where('reservations.reservation_status', 'confirmed')
            ->get();

        $cancelled_walkin_reservations = DB::table('reservations')
            ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'walkin')
            ->where('reservations.reservation_status', 'cancelled')
            ->get();

        $completed_online_reservations = count($completed_online_reservations);
        $booked_online_reservations = count($booked_online_reservations);
        $confirmed_online_reservations = count($confirmed_online_reservations);
        $cancelled_online_reservations = count($cancelled_online_reservations);

        $completed_walkin_reservations = count($completed_walkin_reservations);
        $booked_walkin_reservations = count($booked_walkin_reservations);
        $confirmed_walkin_reservations = count($confirmed_walkin_reservations);
        $cancelled_walkin_reservations = count($cancelled_walkin_reservations);

        $total_covers = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_status', 'Completed')
            ->sum('number_of_people');

        $number_of_tables = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_status', 'NOT LIKE', 'cancelled')
            ->sum('reservation_total_cost');

        $rest_id = DB::table('restaurants')
                   ->select('id')
                   ->where('user_id', $user_id)
                   ->get();

        $reservation_years = DB::table('reservations')
                    ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                    ->select(DB::raw('YEAR(reservations.date_of_reservation) as year'))
                    ->where('restaurants.user_id', $user_id)
                    ->distinct()->get();

        $online_reservation_by_year = array();
        $walkin_reservation_by_year = array();
        $years_label = array();
        $total_revenue_by_year = array();

        foreach ($reservation_years as $year){

            $date_year = $year->year;

            $online_year = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'online')
                ->whereYear('date_of_reservation', date($date_year))
                ->get();
            $online_by_year = count($online_year);
            $walkin_year = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereYear('date_of_reservation', date($date_year))
                ->get();
            //online reservation revenues by month
            $total_yearly_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->whereYear('date_of_reservation', date($date_year))
                ->sum('reservation_total_cost');
            $walkin_by_year = count($walkin_year);
            $online_reservation_by_year[] = $online_by_year;
            $walkin_reservation_by_year[] = $walkin_by_year;
            $years_label[] = $date_year;
            $total_revenue_by_year[] = $total_yearly_revenue;
        }

        $years_label = json_encode($years_label);
        //var_dump($years_label);
        //die();
        $online_reservation_by_year = json_encode($online_reservation_by_year);
        $walkin_reservation_by_year = json_encode($walkin_reservation_by_year);
        $total_revenue_by_year = json_encode($total_revenue_by_year);

        $total_reservations = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->orderByDesc('reservations.date_of_reservation')
            ->orderByDesc('reservations.time_of_reservation')
            ->get();

        $reservations_for_calendar = DB::table('reservations')
            ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
            ->select('reservations.id AS id', 'restaurants.Restaurant_color AS backgroundColor', 'restaurants.Restaurant_color AS borderColor', 
            'reservations.number_of_people AS description', 'restaurants.Restaurant_name',
                DB::raw("CONCAT(reservations.reservation_type,' - ',reservations.user_name) AS title"),
                DB::raw("CONCAT(reservations.date_of_reservation,' ',reservations.time_of_reservation) AS start"))
            ->where('restaurants.user_id', $user_id)
            ->get();

        //online reservation revenues by month
        $january_revenue = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('01'))
            ->sum('reservation_total_cost');

        $february_revenue = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('02'))
            ->sum('reservation_total_cost');

        $march_revenue = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('03'))
            ->sum('reservation_total_cost');

        $april_revenue = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('04'))
            ->sum('reservation_total_cost');

        $may_revenue = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('05'))
            ->sum('reservation_total_cost');

        $june_revenue = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('06'))
            ->sum('reservation_total_cost');

        $july_revenue = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('07'))
            ->sum('reservation_total_cost');

        $august_revenue = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('08'))
            ->sum('reservation_total_cost');

        $september_revenue = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('09'))
            ->sum('reservation_total_cost');

        $october_revenue = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('10'))
            ->sum('reservation_total_cost');

        $november_revenue = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('11'))
            ->sum('reservation_total_cost');

        $december_revenue = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('12'))
            ->sum('reservation_total_cost');

        $revenues_data = [$january_revenue,$february_revenue,$march_revenue, $april_revenue, $may_revenue,$june_revenue,
            $july_revenue,$august_revenue,$september_revenue,$october_revenue,$november_revenue,$december_revenue];

        if(count($reservations_for_calendar) > 0)
        {
            $calendar_data = json_encode($reservations_for_calendar);
        }
        else{
            $calendar_data = "";
            $calendar_data = json_encode($calendar_data);
        }

    	$online_reservations = count($online_reservations);
    	$walkin_reservations = count($walkin_reservations);
    	
        $revenues_data = json_encode($revenues_data);
        
        //var_dump($restaurants);
        //die();
        $labels = array();
        $online_dataset = array();
        $walkin_dataset = array();
        $total_covers_dataset = array();
        $no_of_table_dataset = array();
        
        
        if(!is_null($restaurants))
        {
            foreach ($restaurants as $restaurant)
            {
                $name = $restaurant->Restaurant_name;
                $id = $restaurant->id;
    
                $online_reservations_donut = DB::table('reservations')
                    ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                    ->where('reservations.restaurant_id', $id)
                    ->where('reservations.reservation_type', 'online')
                    ->get();
                    
                $walkin_reservations_donut = DB::table('reservations')
                    ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                    ->where('reservations.restaurant_id', $id)
                    ->where('reservations.reservation_type', 'walkin')
                    ->get();
                    
                $total_covers_donut = DB::table('reservations')
                    ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                    ->where('reservations.restaurant_id', $id)
                    ->where('reservations.reservation_status', 'Completed')
                    ->sum('number_of_people');
                /**$number_of_tables_donut = DB::table('tables')
                    ->join('restaurants', 'tables.restaurant_id', '=', 'restaurants.id')
                    ->where('restaurants.id', $id)
                    ->get();*/
                $table_count_donut = DB::table('reservations')
                    ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                    ->where('reservations.restaurant_id', $id)
                    ->sum('reservation_total_cost');
    
                //$table_count_donut = count($number_of_tables_donut);
    
                $online_donut_data = count($online_reservations_donut);
                $walkin_donut_data = count($walkin_reservations_donut);
    
                $labels[] = $name;
                $online_dataset[] = $online_donut_data;
                $walkin_dataset[] = $walkin_donut_data;
                $total_covers_dataset[] = $total_covers_donut;
                $no_of_table_dataset[] = $table_count_donut;
            }
            
        $labels = json_encode($labels);
        $online_dataset = json_encode($online_dataset);
        $walkin_dataset = json_encode($walkin_dataset);
        $total_covers_dataset = json_encode($total_covers_dataset);
        $no_of_table_dataset = json_encode($no_of_table_dataset);
        }
        else{
            $labels = "";
            $online_dataset = "";
            $walkin_dataset = "";
            $total_covers_dataset = "";
            $no_of_table_dataset = "";
        }

    
        $years_label = json_encode($years_label);

        $online_reservation_by_year = json_encode($online_reservation_by_year);
        $walkin_reservation_by_year = json_encode($walkin_reservation_by_year);
        
        $subscription = DB::table('packages')
                    ->where('user_id', $user_id)
                    ->first();

    	return view('Admin.Dashboards.admin_dashboard',
            compact('walkin_reservations',  'online_reservations','total_covers', 'number_of_tables',
                'restaurants', 'rest_id', 'total_reservations', 'calendar_data', 'revenues_data', 'labels', 'online_dataset',
                'walkin_dataset', 'total_covers_dataset', 'no_of_table_dataset', 'years_label', 'online_reservation_by_year',
                'walkin_reservation_by_year', 'total_revenue_by_year', 'subscription', 'cancelled_walkin_reservations', 
                'completed_walkin_reservations', 'booked_walkin_reservations', 'confirmed_walkin_reservations', 
                'cancelled_online_reservations', 'booked_online_reservations', 'completed_online_reservations', 
                'confirmed_online_reservations'))->with('i', (request()->input('page', 1) - 1) * 5);
            
    }

    public function dashboard_sort_date(Request $request)
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
                ->where('user_id', $user_id)
                ->get();

            //var_dump($user_id);
            //die();
        }

        $request->validate([
            'dashboard_range' => 'required'
        ]);
        
        $start_time = $request->start_time;
        $end_time = $request->end_time;
        
        $subscription = DB::table('packages')
                    ->where('user_id', $user_id)
                    ->first();

        if($request->restaurant_id){
            $request->validate([
                'restaurant_id' => 'required|int'
            ]);
            $date = $request->dashboard_range;
            $new_date = explode('/', $date);
            $start = $new_date[0];
            $end = $new_date[1];
            $restaurant_id = $request->restaurant_id;

            $online_reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->where('reservations.restaurant_id', $restaurant_id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();

            $walkin_reservations = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $restaurant_id)
                ->where('reservations.reservation_type', 'walkin')
                ->where('reservations.reservation_status', 'NOT LIKE', 'cancelled')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();
                
            $booked_online_reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->where('reservations.restaurant_id', $restaurant_id)
                ->where('reservations.reservation_type', 'online')
                ->where('reservations.reservation_status', 'booked')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();

            $completed_online_reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->where('reservations.restaurant_id', $restaurant_id)
                ->where('reservations.reservation_type', 'online')
                ->where('reservations.reservation_status', 'Completed')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();

            $confirmed_online_reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->where('reservations.restaurant_id', $restaurant_id)
                ->where('reservations.reservation_type', 'online')
                ->where('reservations.reservation_status', 'confirmed')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();

            $cancelled_online_reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->where('reservations.restaurant_id', $restaurant_id)
                ->where('reservations.reservation_type', 'online')
                ->where('reservations.reservation_status', 'cancelled')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();
                
            $booked_walkin_reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->where('reservations.restaurant_id', $restaurant_id)
                ->where('reservations.reservation_type', 'walkin')
                ->where('reservations.reservation_status', 'booked')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();

            $completed_walkin_reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->where('reservations.restaurant_id', $restaurant_id)
                ->where('reservations.reservation_type', 'walkin')
                ->where('reservations.reservation_status', 'Completed')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();

            $confirmed_walkin_reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->where('reservations.restaurant_id', $restaurant_id)
                ->where('reservations.reservation_type', 'walkin')
                ->where('reservations.reservation_status', 'confirmed')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();

            $cancelled_walkin_reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->where('reservations.restaurant_id', $restaurant_id)
                ->where('reservations.reservation_type', 'walkin')
                ->where('reservations.reservation_status', 'cancelled')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();
                
            $completed_online_reservations = count($completed_online_reservations);
            $booked_online_reservations = count($booked_online_reservations);
            $confirmed_online_reservations = count($confirmed_online_reservations);
            $cancelled_online_reservations = count($cancelled_online_reservations);
    
            $completed_walkin_reservations = count($completed_walkin_reservations);
            $booked_walkin_reservations = count($booked_walkin_reservations);
            $confirmed_walkin_reservations = count($confirmed_walkin_reservations);
            $cancelled_walkin_reservations = count($cancelled_walkin_reservations);

            $total_covers = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $restaurant_id)
                ->where('reservations.reservation_status', 'Completed')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->sum('number_of_people');

            $number_of_tables = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $restaurant_id)
                ->where('reservations.reservation_status', 'NOT LIKE', 'confirmed')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->sum('reservation_total_cost');

            $rest_id = $restaurant_id;

            $total_reservations = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $restaurant_id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->orderByDesc('reservations.date_of_reservation')
                ->orderByDesc('reservations.time_of_reservation')
                ->get();

            $restaurant = DB::table('restaurants')
                ->where('id', $rest_id)
                ->first();
                
            $rest_type = $restaurant->Reservation_update_type;

            $memberships = DB::table('restaurant_memberships')
                ->where('restaurant_id', $restaurant_id)
                ->get();
    
            $guest_types = DB::table('guest_types')
                ->where('restaurant_id', $restaurant_id)
                ->get();

            $restaurant_name = $restaurant->Restaurant_name;

            $reservations_for_calendar = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->select('reservations.id AS id', 'reservations.number_of_people AS description', 'restaurants.Restaurant_name', 
                'restaurants.Restaurant_color AS borderColor', 'restaurants.Restaurant_color AS backgroundColor',
                    DB::raw("CONCAT(reservations.reservation_type,' - ',reservations.user_name) AS title"),
                    DB::raw("CONCAT(reservations.date_of_reservation,' ',reservations.time_of_reservation) AS start"))
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->where('reservations.restaurant_id', $restaurant_id)
                ->get();

            if(count($reservations_for_calendar) > 0)
            {
                $calendar_data = json_encode($reservations_for_calendar);
            }
            else{
                $calendar_data = "";
                $calendar_data = json_encode($calendar_data);
            }

            $online_reservations = count($online_reservations);
            $walkin_reservations = count($walkin_reservations);
            //$number_of_tables = count($number_of_tables);

            $id = $restaurant_id;

            //online reservations by month
            $january_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $restaurant_id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('01'))
                ->get();

            $january_online = count($january_online);

            $february_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $restaurant_id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('02'))
                ->get();
            $february_online = count($february_online);

            $march_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('03'))
                ->get();
            $march_online = count($march_online);

            $april_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('04'))
                ->get();
            $april_online = count($april_online);

            $may_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('05'))
                ->get();
            $may_online = count($may_online);

            $june_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('06'))
                ->get();
            $june_online = count($june_online);

            $july_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('07'))
                ->get();
            $july_online = count($july_online);

            $august_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('08'))
                ->get();
            $august_online = count($august_online);

            $september_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('09'))
                ->get();
            $september_online = count($september_online);

            $october_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('10'))
                ->get();
            $october_online = count($october_online);

            $november_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('11'))
                ->get();
            $november_online = count($november_online);

            $december_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('12'))
                ->get();
            $december_online = count($december_online);
            $online_reservation_data = [$january_online,$february_online,$march_online, $april_online, $may_online,$june_online,
                $july_online,$august_online,$september_online,$october_online,$november_online,$december_online];

            //walkin reservations by month
            $january_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('01'))
                ->get();
            $january_walkin = count($january_walkin);

            $february_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('02'))
                ->get();
            $february_walkin = count($february_walkin);

            $march_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('03'))
                ->get();
            $march_walkin = count($march_walkin);

            $april_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('04'))
                ->get();
            $april_walkin = count($april_walkin);

            $may_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('05'))
                ->get();
            $may_walkin = count($may_walkin);

            $june_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('06'))
                ->get();
            $june_walkin = count($june_walkin);

            $july_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('07'))
                ->get();
            $july_walkin = count($july_walkin);

            $august_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('08'))
                ->get();
            $august_walkin = count($august_walkin);

            $september_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('09'))
                ->get();
            $september_walkin = count($september_walkin);

            $october_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('10'))
                ->get();
            $october_walkin = count($october_walkin);

            $november_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('11'))
                ->get();
            $november_walkin = count($november_walkin);

            $december_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('12'))
                ->get();
            $december_walkin = count($december_walkin);

            $walkin_reservation_data = [$january_walkin, $february_walkin, $march_walkin, $april_walkin, $may_walkin, $june_walkin,
                $july_walkin, $august_walkin, $september_walkin, $october_walkin, $november_walkin, $december_walkin];

            //online reservation revenues by month
            $january_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('01'))
                ->sum('reservation_total_cost');

            $february_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('02'))
                ->sum('reservation_total_cost');

            $march_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('03'))
                ->sum('reservation_total_cost');

            $april_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('04'))
                ->sum('reservation_total_cost');

            $may_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('05'))
                ->sum('reservation_total_cost');

            $june_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('06'))
                ->sum('reservation_total_cost');

            $july_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('07'))
                ->sum('reservation_total_cost');

            $august_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('08'))
                ->sum('reservation_total_cost');

            $september_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('09'))
                ->sum('reservation_total_cost');

            $october_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('10'))
                ->sum('reservation_total_cost');

            $november_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('11'))
                ->sum('reservation_total_cost');

            $december_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('12'))
                ->sum('reservation_total_cost');

            $revenues_data = [$january_revenue,$february_revenue,$march_revenue, $april_revenue, $may_revenue,$june_revenue,
                $july_revenue,$august_revenue,$september_revenue,$october_revenue,$november_revenue,$december_revenue];

            $walkin_reservation_data = json_encode($walkin_reservation_data);

            $online_reservation_data = json_encode($online_reservation_data);

            $revenues_data = json_encode($revenues_data);
            $labels = "";
            $online_dataset = "";
            $walkin_dataset = "";
            $total_covers_dataset = "";
            $no_of_table_dataset = "";
            $labels = json_encode($labels);
            $online_dataset = json_encode($online_dataset);
            $walkin_dataset = json_encode($walkin_dataset);
            $total_covers_dataset = json_encode($total_covers_dataset);
            $no_of_table_dataset = json_encode($no_of_table_dataset);
            
            $free_tables = DB::table('tables')
                ->where('restaurant_id', $restaurant_id)
                ->where('table_status', '=', 'active')
                ->get();

            $rest_type = $restaurant->Reservation_update_type;

            $memberships = DB::table('restaurant_memberships')
                ->where('restaurant_id', $restaurant_id)
                ->get();

            $guest_types = DB::table('guest_types')
                ->where('restaurant_id', $restaurant_id)
                ->get();

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

            return view('Admin.Dashboards.dashboard_by_date',
                compact('walkin_reservations',  'online_reservations','total_covers', 'number_of_tables',
                    'restaurants', 'rest_id', 'total_reservations', 'restaurant', 'restaurant_id', 'calendar_data',
                    'start', 'end', 'walkin_reservation_data', 'online_reservation_data', 'revenues_data', 'restaurant_name',
                    'labels', 'online_dataset', 'walkin_dataset', 'total_covers_dataset', 'no_of_table_dataset', 'rest_type',
                    'memberships', 'guest_types', 'free_tables', 'hour_count', 'free_hours', 'available_hours', 'start_time',
                    'end_time', 'subscription', 'booked_online_reservations', 'completed_online_reservations', 'confirmed_online_reservations',
                    'cancelled_online_reservations', 'booked_walkin_reservations', 'confirmed_walkin_reservations', 
                    'completed_walkin_reservations', 'cancelled_walkin_reservations'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
        }

        else{
            $date = $request->dashboard_range;
            $new_date = explode('/', $date);
            $start = $new_date[0];
            $end = $new_date[1];

            $online_reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();

            $walkin_reservations = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();
                
            $booked_online_reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->where('reservations.reservation_type', 'online')
                ->where('reservations.reservation_status', 'booked')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();

            $completed_online_reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->where('reservations.reservation_type', 'online')
                ->where('reservations.reservation_status', 'Completed')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();

            $confirmed_online_reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->where('reservations.reservation_type', 'online')
                ->where('reservations.reservation_status', 'confirmed')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();

            $cancelled_online_reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->where('reservations.reservation_type', 'online')
                ->where('reservations.reservation_status', 'cancelled')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();
                
            $booked_walkin_reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->where('reservations.reservation_type', 'walkin')
                ->where('reservations.reservation_status', 'booked')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();

            $completed_walkin_reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->where('reservations.reservation_type', 'walkin')
                ->where('reservations.reservation_status', 'Completed')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();

            $confirmed_walkin_reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->where('reservations.reservation_type', 'walkin')
                ->where('reservations.reservation_status', 'confirmed')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();

            $cancelled_walkin_reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->where('reservations.reservation_type', 'walkin')
                ->where('reservations.reservation_status', 'cancelled')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();

            $completed_online_reservations = count($completed_online_reservations);
            $booked_online_reservations = count($booked_online_reservations);
            $confirmed_online_reservations = count($confirmed_online_reservations);
            $cancelled_online_reservations = count($cancelled_online_reservations);

            $completed_walkin_reservations = count($completed_walkin_reservations);
            $booked_walkin_reservations = count($booked_walkin_reservations);
            $confirmed_walkin_reservations = count($confirmed_walkin_reservations);
            $cancelled_walkin_reservations = count($cancelled_walkin_reservations);

            $total_covers = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_status', 'Completed')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->sum('number_of_people');

            $number_of_tables = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_status', 'NOT LIKE', 'confirmed')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->sum('reservation_total_cost');

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

            $rest_id = DB::table('restaurants')
                ->select('id')
                ->where('user_id', $user_id)
                ->get();

            $total_reservations = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->orderByDesc('reservations.date_of_reservation')
                ->orderByDesc('reservations.time_of_reservation')
                ->get();

            $reservations_for_calendar = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->select('reservations.id AS id',
                    'reservations.number_of_people AS description', 'restaurants.Restaurant_name', 'restaurants.Restaurant_color AS borderColor',
                    'restaurants.Restaurant_color AS backgroundColor',
                    DB::raw("CONCAT(reservations.reservation_type,' - ',reservations.user_name) AS title"),
                    DB::raw("CONCAT(reservations.date_of_reservation,' ',reservations.time_of_reservation) AS start"))
                ->where('restaurants.user_id', $user_id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->get();

            if(count($reservations_for_calendar) > 0)
            {
                $calendar_data = json_encode($reservations_for_calendar);
            }
            else{
                $calendar_data = "";
                $calendar_data = json_encode($calendar_data);
            }

            $online_reservations = count($online_reservations);
            $walkin_reservations = count($walkin_reservations);
            //$number_of_tables = count($number_of_tables);

            //online reservations by month
            $january_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('01'))
                ->get();
            $january_online = count($january_online);

            $february_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('02'))
                ->get();
            $february_online = count($february_online);

            $march_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('03'))
                ->get();
            $march_online = count($march_online);

            $april_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('04'))
                ->get();
            $april_online = count($april_online);

            $may_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('05'))
                ->get();
            $may_online = count($may_online);

            $june_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('06'))
                ->get();
            $june_online = count($june_online);

            $july_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('07'))
                ->get();
            $july_online = count($july_online);

            $august_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('08'))
                ->get();
            $august_online = count($august_online);

            $september_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('09'))
                ->get();
            $september_online = count($september_online);

            $october_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('10'))
                ->get();
            $october_online = count($october_online);

            $november_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('11'))
                ->get();
            $november_online = count($november_online);

            $december_online = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'online')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('12'))
                ->get();
            $december_online = count($december_online);
            $online_reservation_data = [$january_online,$february_online,$march_online, $april_online, $may_online,$june_online,
                $july_online,$august_online,$september_online,$october_online,$november_online,$december_online];

            //walkin reservations by month
            $january_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('01'))
                ->get();
            $january_walkin = count($january_walkin);

            $february_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('02'))
                ->get();
            $february_walkin = count($february_walkin);

            $march_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('03'))
                ->get();
            $march_walkin = count($march_walkin);

            $april_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('04'))
                ->get();
            $april_walkin = count($april_walkin);

            $may_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('05'))
                ->get();
            $may_walkin = count($may_walkin);

            $june_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('06'))
                ->get();
            $june_walkin = count($june_walkin);

            $july_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('07'))
                ->get();
            $july_walkin = count($july_walkin);

            $august_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('08'))
                ->get();
            $august_walkin = count($august_walkin);

            $september_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('09'))
                ->get();
            $september_walkin = count($september_walkin);

            $october_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('10'))
                ->get();
            $october_walkin = count($october_walkin);

            $november_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('11'))
                ->get();
            $november_walkin = count($november_walkin);

            $december_walkin = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->where('reservations.reservation_type', 'walkin')
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('12'))
                ->get();
            $december_walkin = count($december_walkin);

            $walkin_reservation_data = [$january_walkin, $february_walkin, $march_walkin, $april_walkin, $may_walkin, $june_walkin,
                $july_walkin, $august_walkin, $september_walkin, $october_walkin, $november_walkin, $december_walkin];

            //online reservation revenues by month
            $january_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('01'))
                ->sum('reservation_total_cost');

            $february_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('02'))
                ->sum('reservation_total_cost');

            $march_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('03'))
                ->sum('reservation_total_cost');

            $april_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('04'))
                ->sum('reservation_total_cost');

            $may_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('05'))
                ->sum('reservation_total_cost');

            $june_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('06'))
                ->sum('reservation_total_cost');

            $july_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('07'))
                ->sum('reservation_total_cost');

            $august_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('08'))
                ->sum('reservation_total_cost');

            $september_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('09'))
                ->sum('reservation_total_cost');

            $october_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('10'))
                ->sum('reservation_total_cost');

            $november_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('11'))
                ->sum('reservation_total_cost');

            $december_revenue = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.user_id', $user_id)
                ->whereBetween('reservations.date_of_reservation', [$start, $end])
                ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                ->whereMonth('date_of_reservation', date('12'))
                ->sum('reservation_total_cost');

            $revenues_data = [$january_revenue,$february_revenue,$march_revenue, $april_revenue, $may_revenue,$june_revenue,
                $july_revenue,$august_revenue,$september_revenue,$october_revenue,$november_revenue,$december_revenue];

            $walkin_reservation_data = json_encode($walkin_reservation_data);

            $online_reservation_data = json_encode($online_reservation_data);

            $revenues_data = json_encode($revenues_data);

            foreach ($restaurants as $restaurant)
            {
                $name = $restaurant->Restaurant_name;
                $id = $restaurant->id;

                $online_reservations_donut = DB::table('reservations')
                    ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                    ->where('reservations.restaurant_id', $id)
                    ->where('reservations.reservation_type', 'online')
                    ->whereBetween('reservations.date_of_reservation', [$start, $end])
                    ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                    ->get();
                $walkin_reservations_donut = DB::table('reservations')
                    ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                    ->where('reservations.restaurant_id', $id)
                    ->where('reservations.reservation_type', 'walkin')
                    ->whereBetween('reservations.date_of_reservation', [$start, $end])
                    ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                    ->get();
                $total_covers_donut = DB::table('reservations')
                    ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                    ->where('reservations.restaurant_id', $id)
                    ->where('reservations.reservation_status', 'Completed')
                    ->whereBetween('reservations.date_of_reservation', [$start, $end])
                    ->whereBetween('reservations.time_of_reservation', [$start_time, $end_time])
                    ->sum('number_of_people');
                /**$number_of_tables_donut = DB::table('tables')
                    ->join('restaurants', 'tables.restaurant_id', '=', 'restaurants.id')
                    ->where('restaurants.id', $id)
                    ->whereBetween('tables.created_at', [$start, $end])
                    ->get();*/
                    
                $table_count_donut = DB::table('reservations')
                    ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                    ->where('reservations.restaurant_id', $id)
                    ->whereBetween('restaurants.created_at', [$start, $end])
                    ->sum('reservation_total_cost');

                //$table_count_donut = count($number_of_tables_donut);

                $online_donut_data = count($online_reservations_donut);
                $walkin_donut_data = count($walkin_reservations_donut);

                $labels[] = $name;
                $online_dataset[] = $online_donut_data;
                $walkin_dataset[] = $walkin_donut_data;
                $total_covers_dataset[] = $total_covers_donut;
                $no_of_table_dataset[] = $table_count_donut;
            }

            $labels = json_encode($labels);
            $online_dataset = json_encode($online_dataset);
            $walkin_dataset = json_encode($walkin_dataset);
            $total_covers_dataset = json_encode($total_covers_dataset);
            $no_of_table_dataset = json_encode($no_of_table_dataset);

            $restaurant_name = "";
            $rest_type = "";
            $memberships = "";
            $guest_types = "";
            $free_tables = "";
            $hour_count = "";
            $free_hours = "";
            $available_hours = "";
            $restaurant_id = "";

            return view('Admin.Dashboards.dashboard_by_date',
                compact('walkin_reservations',  'online_reservations','total_covers', 'number_of_tables',
                    'restaurants', 'rest_id', 'total_reservations', 'calendar_data', 'start', 'end', 'walkin_reservation_data',
                    'online_reservation_data', 'revenues_data', 'restaurant_name', 'labels', 'online_dataset', 'walkin_dataset',
                    'total_covers_dataset', 'no_of_table_dataset', 'rest_type', 'memberships', 'guest_types', 'free_tables', 
                    'hour_count', 'free_hours', 'available_hours', 'restaurant_id', 'start_time', 'end_time', 'subscription', 
                    'cancelled_walkin_reservations', 'completed_walkin_reservations', 'booked_walkin_reservations', 
                    'confirmed_walkin_reservations', 'cancelled_online_reservations', 'booked_online_reservations', 
                    'completed_online_reservations', 'confirmed_online_reservations'))->with('i', (request()->input('page', 1) - 1) * 5);
        }
        //$restaurant_id = $request->restaurant_id;

    }

    public function restaurant_dashboard($id)
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
            ->where('user_id', $user_id)
            ->get();
        }

        $restaurant_id = $id;
        $online_reservations = DB::table('reservations')
            ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
            ->where('reservations.restaurant_id', $restaurant_id)
            ->where('reservations.reservation_type', 'online')
            ->get();

        $walkin_reservations = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $restaurant_id)
            ->where('reservations.reservation_type', 'walkin')
            ->get();

        $total_covers = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $restaurant_id)
            ->where('reservations.reservation_status', 'confirmed')
            ->sum('number_of_people');

        $number_of_tables = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $restaurant_id)
            ->where('reservations.reservation_status', 'NOT LIKE', 'confirmed')
            ->sum('reservation_total_cost');

        $rest_id = $restaurant_id;

        $total_reservations = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $restaurant_id)
            ->orderByDesc('reservations.date_of_reservation')
            ->orderByDesc('reservations.time_of_reservation')
            ->get();

        $restaurant = DB::table('restaurants')
            ->where('id', $rest_id)
            ->first();
            
        $rest_type = $restaurant->Reservation_update_type;

        $memberships = DB::table('restaurant_memberships')
            ->where('restaurant_id', $restaurant_id)
            ->get();

        $guest_types = DB::table('guest_types')
            ->where('restaurant_id', $restaurant_id)
            ->get();

        $reservations_for_calendar = DB::table('reservations')
            ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
            ->select('reservations.id AS id',
                'reservations.number_of_people AS description', 'restaurants.Restaurant_name', 'restaurants.Restaurant_color AS borderColor',
                'restaurants.Restaurant_color AS backgroundColor',
                DB::raw("CONCAT(reservations.reservation_type,' - ',reservations.user_name) AS title"),
                DB::raw("CONCAT(reservations.date_of_reservation,' ',reservations.time_of_reservation) AS start"))
            ->where('reservations.restaurant_id', $restaurant_id)
            ->get();

        //online reservations by month
        $january_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('01'))
            ->get();

        $january_online = count($january_online);

        $february_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('02'))
            ->get();
        $february_online = count($february_online);

        $march_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('03'))
            ->get();
        $march_online = count($march_online);

        $april_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('04'))
            ->get();
        $april_online = count($april_online);

        $may_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('05'))
            ->get();
        $may_online = count($may_online);

        $june_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('06'))
            ->get();
        $june_online = count($june_online);

        $july_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('07'))
            ->get();
        $july_online = count($july_online);

        $august_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('08'))
            ->get();
        $august_online = count($august_online);

        $september_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('09'))
            ->get();
        $september_online = count($september_online);

        $october_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('10'))
            ->get();
        $october_online = count($october_online);

        $november_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('11'))
            ->get();
        $november_online = count($november_online);

        $december_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('12'))
            ->get();
        $december_online = count($december_online);
        $online_reservation_data = [$january_online,$february_online,$march_online, $april_online, $may_online,$june_online,
            $july_online,$august_online,$september_online,$october_online,$november_online,$december_online];

        //walkin reservations by month
        $january_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('01'))
            ->get();
        $january_walkin = count($january_walkin);

        $february_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('02'))
            ->get();
        $february_walkin = count($february_walkin);

        $march_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('03'))
            ->get();
        $march_walkin = count($march_walkin);

        $april_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('04'))
            ->get();
        $april_walkin = count($april_walkin);

        $may_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('05'))
            ->get();
        $may_walkin = count($may_walkin);

        $june_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('06'))
            ->get();
        $june_walkin = count($june_walkin);

        $july_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('07'))
            ->get();
        $july_walkin = count($july_walkin);

        $august_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('08'))
            ->get();
        $august_walkin = count($august_walkin);

        $september_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('09'))
            ->get();
        $september_walkin = count($september_walkin);

        $october_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('10'))
            ->get();
        $october_walkin = count($october_walkin);

        $november_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('11'))
            ->get();
        $november_walkin = count($november_walkin);

        $december_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('12'))
            ->get();
        $december_walkin = count($december_walkin);

        $walkin_reservation_data = [$january_walkin, $february_walkin, $march_walkin, $april_walkin, $may_walkin, $june_walkin,
            $july_walkin, $august_walkin, $september_walkin, $october_walkin, $november_walkin, $december_walkin];

        //online reservations by month
        $january_online_revenues = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('01'))
            ->sum('reservation_total_cost');

        $february_online_revenues = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('02'))
            ->sum('reservation_total_cost');

        $march_online_revenues = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('03'))
            ->sum('reservation_total_cost');

        $april_online_revenues = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('04'))
            ->sum('reservation_total_cost');

        $may_online_revenues = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('05'))
            ->sum('reservation_total_cost');

        $june_online_revenues = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('06'))
            ->sum('reservation_total_cost');

        $july_online_revenues = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('07'))
            ->sum('reservation_total_cost');

        $august_online_revenues = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('08'))
            ->sum('reservation_total_cost');

        $september_online_revenues = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('09'))
            ->sum('reservation_total_cost');

        $october_online_revenues = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('10'))
            ->sum('reservation_total_cost');

        $november_online_revenues = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('11'))
            ->sum('reservation_total_cost');

        $december_online_revenues = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('12'))
            ->sum('reservation_total_cost');

        $revenues_data = [$january_online_revenues,$february_online_revenues, $march_online_revenues, $april_online_revenues,
            $may_online_revenues, $june_online_revenues, $july_online_revenues, $august_online_revenues, $september_online_revenues,
            $october_online_revenues,$november_online_revenues,$december_online_revenues];

        if(count($reservations_for_calendar) > 0)
        {
            $calendar_data = json_encode($reservations_for_calendar);
        }
        else{
            $calendar_data = "";
            $calendar_data = json_encode($calendar_data);
        }

        $online_reservations = count($online_reservations);
        $walkin_reservations = count($walkin_reservations);
        //$number_of_tables = count($number_of_tables);
        $online_reservation_data = json_encode($online_reservation_data);
        $walkin_reservation_data = json_encode($walkin_reservation_data);
        $revenues_data = json_encode($revenues_data);
        
        $free_tables = DB::table('tables')
            ->where('restaurant_id', $restaurant_id)
            ->where('table_status', '=', 'active')
            ->get();

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

        return view('Admin.Dashboards.restaurant_dashboard',
            compact('walkin_reservations',  'online_reservations','total_covers', 'number_of_tables',
                'restaurants', 'rest_id', 'total_reservations', 'restaurant', 'restaurant_id', 'calendar_data',
                'online_reservation_data', 'walkin_reservation_data', 'revenues_data', 'rest_type', 'memberships',
                'guest_types', 'free_tables', 'hour_count', 'free_hours', 'available_hours'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function reservation_graph_data()
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

        //online reservations by month
        $january_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('01'))
            ->get();
        $january_online = count($january_online);

        $february_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('02'))
            ->get();
        $february_online = count($february_online);

        $march_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('03'))
            ->get();
        $march_online = count($march_online);

        $april_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('04'))
            ->get();
        $april_online = count($april_online);

        $may_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('05'))
            ->get();
        $may_online = count($may_online);

        $june_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('06'))
            ->get();
        $june_online = count($june_online);

        $july_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('07'))
            ->get();
        $july_online = count($july_online);

        $august_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('08'))
            ->get();
        $august_online = count($august_online);

        $september_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('09'))
            ->get();
        $september_online = count($september_online);

        $october_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('10'))
            ->get();
        $october_online = count($october_online);

        $november_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('11'))
            ->get();
        $november_online = count($november_online);

        $december_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('12'))
            ->get();
        $december_online = count($december_online);
        $online_reservation_data = [$january_online,$february_online,$march_online, $april_online, $may_online,$june_online,
            $july_online,$august_online,$september_online,$october_online,$november_online,$december_online];

        //walkin reservations by month
        $january_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('01'))
            ->get();
        $january_walkin = count($january_walkin);

        $february_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('02'))
            ->get();
        $february_walkin = count($february_walkin);

        $march_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('03'))
            ->get();
        $march_walkin = count($march_walkin);

        $april_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('04'))
            ->get();
        $april_walkin = count($april_walkin);

        $may_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('05'))
            ->get();
        $may_walkin = count($may_walkin);

        $june_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('06'))
            ->get();
        $june_walkin = count($june_walkin);

        $july_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('07'))
            ->get();
        $july_walkin = count($july_walkin);

        $august_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('08'))
            ->get();
        $august_walkin = count($august_walkin);

        $september_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('09'))
            ->get();
        $september_walkin = count($september_walkin);

        $october_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('10'))
            ->get();
        $october_walkin = count($october_walkin);

        $november_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('11'))
            ->get();
        $november_walkin = count($november_walkin);

        $december_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('12'))
            ->get();
        $december_walkin = count($december_walkin);

        $walkin_reservation_data = [$january_walkin, $february_walkin, $march_walkin, $april_walkin, $may_walkin, $june_walkin,
            $july_walkin, $august_walkin, $september_walkin, $october_walkin, $november_walkin, $december_walkin];

        return response()->json(array('success' => true, 'online' => $online_reservation_data, 'walkin' => $walkin_reservation_data,
            'message' => 'Online and WalkIn Reservation data is sent.'));
    }

    //fetch data for revenues graph
    public function revenues_graph_data()
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

            //var_dump($user_id);
            //die();
        }

        //online reservations by month
        $january_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('01'))
            ->sum('reservation_total_cost');

        $february_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('02'))
            ->sum('reservation_total_cost');

        $march_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('03'))
            ->sum('reservation_total_cost');

        $april_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('04'))
            ->sum('reservation_total_cost');

        $may_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('05'))
            ->sum('reservation_total_cost');

        $june_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('06'))
            ->sum('reservation_total_cost');

        $july_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('07'))
            ->sum('reservation_total_cost');

        $august_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('08'))
            ->sum('reservation_total_cost');

        $september_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('09'))
            ->sum('reservation_total_cost');

        $october_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('10'))
            ->sum('reservation_total_cost');

        $november_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('11'))
            ->sum('reservation_total_cost');

        $december_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.user_id', $user_id)
            ->whereMonth('date_of_reservation', date('12'))
            ->sum('reservation_total_cost');

        $revenues_data = [$january_online,$february_online,$march_online, $april_online, $may_online,$june_online,
            $july_online,$august_online,$september_online,$october_online,$november_online,$december_online];

        return response()->json(array('success' => false, 'revenue' => $revenues_data,
            'message' => 'Something is wrong with your inputs, check again.'));
    }

    public function restaurant_reservation_graph_data($id)
    {
        //online reservations by month
        $january_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('01'))
            ->get();

        $january_online = count($january_online);

        $february_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('02'))
            ->get();
        $february_online = count($february_online);

        $march_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('03'))
            ->get();
        $march_online = count($march_online);

        $april_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('04'))
            ->get();
        $april_online = count($april_online);

        $may_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('05'))
            ->get();
        $may_online = count($may_online);

        $june_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('06'))
            ->get();
        $june_online = count($june_online);

        $july_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('07'))
            ->get();
        $july_online = count($july_online);

        $august_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('08'))
            ->get();
        $august_online = count($august_online);

        $september_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('09'))
            ->get();
        $september_online = count($september_online);

        $october_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('10'))
            ->get();
        $october_online = count($october_online);

        $november_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('11'))
            ->get();
        $november_online = count($november_online);

        $december_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'online')
            ->whereMonth('date_of_reservation', date('12'))
            ->get();
        $december_online = count($december_online);
        $online_reservation_data = [$january_online,$february_online,$march_online, $april_online, $may_online,$june_online,
            $july_online,$august_online,$september_online,$october_online,$november_online,$december_online];

        //walkin reservations by month
        $january_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('01'))
            ->get();
        $january_walkin = count($january_walkin);

        $february_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('02'))
            ->get();
        $february_walkin = count($february_walkin);

        $march_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('03'))
            ->get();
        $march_walkin = count($march_walkin);

        $april_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('04'))
            ->get();
        $april_walkin = count($april_walkin);

        $may_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('05'))
            ->get();
        $may_walkin = count($may_walkin);

        $june_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('06'))
            ->get();
        $june_walkin = count($june_walkin);

        $july_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('07'))
            ->get();
        $july_walkin = count($july_walkin);

        $august_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('08'))
            ->get();
        $august_walkin = count($august_walkin);

        $september_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('09'))
            ->get();
        $september_walkin = count($september_walkin);

        $october_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('10'))
            ->get();
        $october_walkin = count($october_walkin);

        $november_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('11'))
            ->get();
        $november_walkin = count($november_walkin);

        $december_walkin = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->where('reservations.reservation_type', 'walkin')
            ->whereMonth('date_of_reservation', date('12'))
            ->get();
        $december_walkin = count($december_walkin);

        $walkin_reservation_data = [$january_walkin, $february_walkin, $march_walkin, $april_walkin, $may_walkin, $june_walkin,
            $july_walkin, $august_walkin, $september_walkin, $october_walkin, $november_walkin, $december_walkin];

        return response()->json(array('success' => true, 'online' => $online_reservation_data, 'walkin' => $walkin_reservation_data,
            'message' => 'Online and WalkIn Reservation data is sent.'));
    }

    //fetch data for revenues graph
    public function restaurant_revenues_graph_data($id)
    {
        //online reservations by month
        $january_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('01'))
            ->sum('reservation_total_cost');

        $february_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('02'))
            ->sum('reservation_total_cost');

        $march_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('03'))
            ->sum('reservation_total_cost');

        $april_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('04'))
            ->sum('reservation_total_cost');

        $may_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('05'))
            ->sum('reservation_total_cost');

        $june_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('06'))
            ->sum('reservation_total_cost');

        $july_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('07'))
            ->sum('reservation_total_cost');

        $august_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('08'))
            ->sum('reservation_total_cost');

        $september_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('09'))
            ->sum('reservation_total_cost');

        $october_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('10'))
            ->sum('reservation_total_cost');

        $november_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('11'))
            ->sum('reservation_total_cost');

        $december_online = DB::table('reservations')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->where('reservations.restaurant_id', $id)
            ->whereMonth('date_of_reservation', date('12'))
            ->sum('reservation_total_cost');

        $revenues_data = [$january_online,$february_online,$march_online, $april_online, $may_online,$june_online,
            $july_online,$august_online,$september_online,$october_online,$november_online,$december_online];

        return response()->json(array('success' => false, 'revenue' => $revenues_data,
            'message' => 'Something is wrong with your inputs, check again.'));
    }

    public function donut_data()
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
            ->select('id', 'Restaurant_name')
            ->where('user_id', $user_id)
            ->get();
        }

        $labels = array();
        $online_dataset = array();
        $walkin_dataset = array();
        $total_covers_dataset = array();
        $no_of_table_dataset = array();

        foreach ($restaurants as $restaurant)
        {
            $name = $restaurant->Restaurant_name;
            $id = $restaurant->id;

            $online_reservations = DB::table('reservations')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'online')
                ->get();
            $walkin_reservations = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_type', 'walkin')
                ->get();
            $total_covers = DB::table('reservations')
                ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                ->where('reservations.restaurant_id', $id)
                ->where('reservations.reservation_status', 'Completed')
                ->sum('number_of_people');
            /**$number_of_tables = DB::table('tables')
                ->join('restaurants', 'tables.restaurant_id', '=', 'restaurants.id')
                ->where('restaurants.id', $id)
                ->get();*/
            //$table_count = count($number_of_tables);
            $table_count = DB::table('reservations')
                    ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
                    ->where('reservations.restaurant_id', $id)
                    ->sum('reservation_total_cost');

            $online_donut_data = count($online_reservations);
            $walkin_donut_data = count($walkin_reservations);

            $labels[] = $name;
            $online_dataset[] = $online_donut_data;
            $walkin_dataset[] = $walkin_donut_data;
            $total_covers_dataset[] = $total_covers;
            $no_of_table_dataset[] = $table_count;
        }

        $donut_dataset = [$labels, $online_dataset, $walkin_dataset, $total_covers_dataset, $no_of_table_dataset];

        return json_encode($donut_dataset);

    }

}
