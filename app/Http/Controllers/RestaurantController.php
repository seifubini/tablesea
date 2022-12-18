<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Feedback;
use App\Models\promotion;
use App\Models\Category;
use App\Models\Restaurant_type;
use App\Models\Restaurant_User;
use App\Models\RestaurantHours;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RestaurantController extends Controller
{

    public function __construct()
	{
		$this->middleware('auth');
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
                ->where('restaurant__users.user_id', Auth::user()->id)
                ->get();
    
                $restaurants = DB::table('restaurants')
                    ->join('restaurant__users', 'restaurant__users.restaurant_id', '=', 'restaurants.id')
                    ->select('restaurants.*')
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

        $restaurant_count = count($restaurants);

		return view('Admin.Restaurant.index', compact('restaurants', 'restaurant_count'))
		->with('i', (request()->input('page', 1) - 1) * 5);
	}
	
	public function get_restaurant_type($id)
    {
        $restaurant = DB::table('restaurants')
            ->where('id', $id)
            ->first();

        return json_encode($restaurant);

    }

    public function get_guest_type($id)
    {
        $guest_types = DB::table('guest_types')
            ->where('restaurant_id', $id)
            ->get();

        return json_encode($guest_types);
    }

    public function get_memberships($id)
    {
        $memberships = DB::table('restaurant_memberships')
            ->where('restaurant_id', $id)
            ->get();

        return json_encode($memberships);
    }

    public function get_free_tables($id)
    {
        $free_tables = DB::table('tables')
            ->where('restaurant_id', $id)
            ->where('table_status', '=', 'active')
            ->where('table_is_booked', 'yes')
            ->get();

        return json_encode($free_tables);
    }

	/**public function get_restaurant($id)
    {
        $restaurant_id = $id;

        return redirect()->route('create_reservation', 'restaurant_id');
        //return view('Admin.Restaurant.index', compact('restaurants', 'id'))
            //->with('i', (request()->input('page', 1) - 1) * 5);
    }*/

	public function create()
	{
        if(Auth::user()->user_type == 'Manager' || Auth::user()->user_type == 'Booking_Manager')
        {
            $info = DB::table('users')
                ->where('id', Auth::user()->id)
                ->first();
            $user_id = $info->created_by;

            $restaurants = DB::table('restaurants')
                ->join('restaurant__users', 'restaurant__users.restaurant_id', '=', 'restaurants.id')
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

        if(count($restaurants) == Auth::user()->no_of_restaurants){
            return redirect()->route('restaurants.index')
                ->with('error', 'Restaurant not Created, You have created enough restaurants for your Quota');
        }
        else{
            return view('Admin.Restaurant.create', compact('restaurants'));
        }

	}

	/**
     * Store a new empoyee in database
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\Response
     */
	public function store(Request $request)
	{
		$request->validate([
			'Restaurant_name' => 'required|string',
			'Restaurant_phone' => 'required|string|unique:restaurants',
			'Restaurant_address' => 'required|string',
			'Restaurant_photo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'Restaurant_logo' => 'image|mimes:jpeg,png,jpg|max:1024',
			'user_id' => 'required',
            'Restaurant_description' => 'required|string|max:500',
            'Restaurant_coordinates' => 'required|string',
            'restaurant_opening_hour' => 'required|date_format:H:i:s',
            'restaurant_closing_hour' => 'required|date_format:H:i:s',
            'Restaurant_duration' => 'required|int',
            'Restaurant_max_capacity' => 'required|int',
            'Restaurant_Country' => 'required|string',
            'Restaurant_City' => 'required|string',
            'Restaurant_price_range' => 'required|string',
            'Restaurant_type' => 'string|max:50',
            'Reservation_update_type' => 'required|string',
            'restaurant_currency' => 'required|string'
		]);
		
		if($request->breakfast_start_hour != '' || $request->breakfast_end_hour != '')
        {
            if($request->breakfast_start_hour < $request->restaurant_opening_hour)
            {
                return redirect()->back()
                    ->with('error', 'Restaurant breakfast hour not updated, 
                    You cannot define a dinning hour before the restaurant is opened.');
            }
            elseif($request->breakfast_end_hour > $request->restaurant_closing_hour)
            {
                return redirect()->back()
                    ->with('error', 'Restaurant breakfast hour not updated, 
                    You cannot define a dinning hour after the restaurant is closed.');
            }
        }
        if($request->lunch_start_hour != '' || $request->lunch_end_hour != '')
        {
            if($request->lunch_start_hour < $request->restaurant_opening_hour)
            {
                return redirect()->back()
                    ->with('error', 'Restaurant lunch hour not updated, 
                    You cannot define a dinning hour before the restaurant is opened.');
            }
            elseif($request->lunch_end_hour > $request->restaurant_closing_hour)
            {
                return redirect()->back()
                    ->with('error', 'Restaurant lunch hour not updated, 
                    You cannot define a dinning hour after the restaurant is closed.');
            }
        }
        if($request->dinner_start_hour != '' || $request->dinner_end_hour)
        {
            if($request->dinner_start_hour < $request->restaurant_opening_hour)
            {
                return redirect()->back()
                    ->with('error', 'Restaurant lunch hour not updated, 
                    You cannot define a dinning hour before the restaurant is opened.');
            }
            elseif($request->dinner_end_hour > $request->restaurant_closing_hour)
            {
                return redirect()->back()
                    ->with('error', 'Restaurant lunch hour not updated, 
                    You cannot define a dinning hour after the restaurant is closed.');
            }
        }
        
        if(Auth::user()->user_type != 'Restaurant')
        {
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }
        if($request->monday == ""){
            $monday = 'no';
        }
        else{
            $monday = 'yes';
        }
        if($request->tuesday == ""){
            $tuesday = 'no';
        }
        else{
            $tuesday = 'yes';
        }
        if($request->wednesday == ""){
            $wednesday = 'no';
        }
        else{
            $wednesday = 'yes';
        }
        if($request->thursday == ""){
            $thursday = 'no';
        }
        else{
            $thursday = 'yes';
        }
        if($request->friday == ""){
            $friday = 'no';
        }
        else{
            $friday = 'yes';
        }
        if($request->saturday == ""){
            $saturday = 'no';
        }
        else{
            $saturday = 'yes';
        }
        if($request->sunday == ""){
            $sunday = 'no';
        }
        else{
            $sunday = 'yes';
        }

        $restaurants = DB::table('restaurants')
            ->where('user_id', $request->user_id)->get();
        
        if(count($restaurants) == Auth::user()->no_of_restaurants){
            return redirect()->route('restaurants.index')
                ->with('error', 'Restaurant not Created, You have created enough number of Restaurants with this Account.');
        }
        elseif(count($restaurants) > Auth::user()->no_of_restaurants)
        {
            return redirect()->route('restaurants.index')
                ->with('error', 'Restaurant not Created, You cannot create more than 2 Restaurants with this Account.');
        }
        else{
            $imageName = $request->Restaurant_name.time().'.'.$request->Restaurant_photo->extension();
            $logoName = $request->Restaurant_name.time().'.'.$request->Restaurant_logo->extension();

            $request->Restaurant_photo->move(public_path('images/restaurant'), $imageName);
            $request->Restaurant_logo->move(public_path('images/restaurant/logo'), $logoName);

            $info = new Restaurant;

            $info->Restaurant_email = Auth::user()->email;
            $info->Restaurant_name = $request->Restaurant_name;
            $info->Restaurant_phone = $request->Restaurant_phone;
            $info->Restaurant_address = $request->Restaurant_address;
            $info->Restaurant_hours = $request->Restaurant_hours;
            $info->Restaurant_photo = $imageName;
            $info->Restaurant_logo = $logoName;
            $info->user_id = $request->user_id;
            $info->Restaurant_description = $request->Restaurant_description;
            $info->Restaurant_type = $request->Restaurant_type;
            $info->type_id = $request->type_id;
            $info->Restaurant_duration = $request->Restaurant_duration;
            $info->restaurant_opening_hour = $request->restaurant_opening_hour;
            $info->restaurant_closing_hour = $request->restaurant_closing_hour;
            $info->Restaurant_coordinates = $request->Restaurant_coordinates;
            $info->Restaurant_max_capacity = $request->Restaurant_max_capacity;
            $info->Restaurant_price_range = $request->Restaurant_price_range;
            $info->Restaurant_Country = $request->Restaurant_Country;
            $info->Restaurant_City = $request->Restaurant_City;
            $info->Restaurant_type = $request->Restaurant_type;
            $info->restaurant_currency = $request->restaurant_currency;
            $info->Restaurant_color = $request->Restaurant_color;
            $info->monday_open = $monday;
            $info->tuesday_open = $tuesday;
            $info->wednesday_open = $wednesday;
            $info->thursday_open = $thursday;
            $info->friday_open = $friday;
            $info->saturday_open = $saturday;
            $info->sunday_open = $sunday;
            $info->Reservation_update_type = $request->Reservation_update_type;

            $info->save();
            $restaurant_id = $info->id;

            if($request->breakfast_start_hour != '' || $request->breakfast_end_hour != '')
            {
                if($request->breakfast_start_hour < $request->restaurant_opening_hour)
                {
                    return redirect()->back()
                        ->with('error', 'Restaurant breakfast hour not updated, 
                    You cannot define a dinning hour before the restaurant is opened.');
                }
                elseif($request->breakfast_end_hour > $request->restaurant_closing_hour)
                {
                    return redirect()->back()
                        ->with('error', 'Restaurant breakfast hour not updated, 
                    You cannot define a dinning hour after the restaurant is closed.');
                }

                $data = new RestaurantHours;

                $data->start_hour = $request->breakfast_start_hour;
                $data->end_hour = $request->breakfast_end_hour;
                $data->hour_name = 'Breakfast_Hours';
                $data->restaurant_id = $restaurant_id;
                $data->restaurant_name = $request->Restaurant_name;

                $data->save();

            }
            if($request->lunch_start_hour != '' || $request->lunch_end_hour != '')
            {
                if($request->lunch_start_hour < $request->restaurant_opening_hour)
                {
                    return redirect()->back()
                        ->with('error', 'Restaurant lunch hour not updated, 
                    You cannot define a dinning hour before the restaurant is opened.');
                }
                elseif($request->lunch_end_hour > $request->restaurant_closing_hour)
                {
                    return redirect()->back()
                        ->with('error', 'Restaurant lunch hour not updated, 
                    You cannot define a dinning hour after the restaurant is closed.');
                }

                $data = new RestaurantHours;

                $data->start_hour = $request->lunch_start_hour;
                $data->end_hour = $request->lunch_end_hour;
                $data->hour_name = 'Lunch_Hours';
                $data->restaurant_id = $restaurant_id;
                $data->restaurant_name = $request->Restaurant_name;

                $data->save();
            }
            if($request->dinner_start_hour != '' || $request->dinner_end_hour)
            {
                if($request->dinner_start_hour < $request->restaurant_opening_hour)
                {
                    return redirect()->back()
                        ->with('error', 'Restaurant dinner hour not updated, 
                    You cannot define a dinning hour before the restaurant is opened.');
                }
                elseif($request->dinner_end_hour > $request->restaurant_closing_hour)
                {
                    return redirect()->back()
                        ->with('error', 'Restaurant dinner hour not updated, 
                    You cannot define a dinning hour after the restaurant is closed.');
                }

                $data = new RestaurantHours;

                $data->start_hour = $request->dinner_start_hour;
                $data->end_hour = $request->dinner_end_hour;
                $data->hour_name = 'Dinner_Hours';
                $data->restaurant_id = $restaurant_id;
                $data->restaurant_name = $request->Restaurant_name;

                $data->save();
            }

            return redirect()->route('restaurants.index')
                ->with('success', 'Restaurant Created Successfully.');
        }
	}

	public function add_user(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_type' => 'required|string',
            'created_by_email' => 'required|email',
            'restaurant_id' => 'required'
        ]);

        $owner = DB::table('users')
            ->where('email', $request->created_by_email)
            ->first();

        if(Auth::user()->user_type == 'Restaurant' || Auth::user()->user_type == 'Administrator')
        {
            $user_id = $user_id = $owner->id;
            //var_dump($data);
            //var_dump($user_id);
            //die();

            $my_users = DB::table('users')
                ->where('created_by', $user_id)
                ->get();

            if($my_users != "")
            {
                $count = count($my_users);
            }
            else{
                $count = 0;
            }
            
            if($count >= 9)
            {
                return redirect()->back()
                    ->with('error', "You cannot create more than 8 users for a single subscription.");
            }

            $user_email = $request->created_by_email;

            $users = DB::table('users')
                ->select('id')
                ->where('email', '=', $user_email)->first();

            $id = $users->id;

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => $request->user_type,
                'created_by' => $id
            ]);

            $restaurant_id = $request->restaurant_id;
            $count = count($restaurant_id);
            $rest_id = $request->restaurant_id;

            for($i = 0; $i < $count; $i++)
            {
                $restaurant_id = $rest_id[$i];

                Restaurant_User::create([
                    'user_id' => $user->id,
                    'restaurant_id' => $restaurant_id,
                    'user_role' => $request->user_type,
                    'created_by' => $id
                ]);

                event(new Registered($user));

                //var_dump($id);
                //die();
            }

            return redirect()->back()
                ->with('success', 'User Created Successfully.');
        }
        else
        {
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }
    }

    public function Restaurant_users()
    {
        if(Auth::user()->user_type != 'Restaurant')
        {
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }

        if(Auth::user()->user_type == 'Manager' || Auth::user()->user_type == 'Booking_Manager')
        {
            $info = DB::table('users')
                ->where('id', Auth::user()->id)
                ->first();
            $user_id = $info->created_by;

            $restaurants = DB::table('restaurants')
                ->join('restaurant__users', 'restaurant__users.restaurant_id', '=', 'restaurants.id')
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

        $my_users = DB::table('restaurant__users')
            ->join('users', 'users.id', '=', 'restaurant__users.user_id')
            ->join('restaurants', 'restaurants.id', '=', 'restaurant__users.restaurant_id')
            ->select('restaurant__users.id', 'restaurant__users.user_id', 'restaurant__users.restaurant_id',
                'restaurant__users.user_role', 'restaurant__users.created_by', 'users.name', 'users.email',
                'restaurant__users.created_at', 'restaurants.Restaurant_name')
            ->where('users.created_by', $user_id)
            ->distinct()->get();
            
            //$my_users = $collection->unique('email');
            //$my_users->all();
            
                //var_dump($my_users);
                //die();

        return view('Admin.Restaurant.restaurant_users', compact('restaurants', 'my_users'))
            ->with('i', (request()->input('page', 1) - 1) * 5);

    }

    public function edit_user(Request $request, Restaurant_User $restaurant_User, $user_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_type' => 'required|string',
            'created_by_email' => 'required|email',
            'restaurant_id' => 'required',
            'restaurant_user_id' => 'required'
        ]);

        if(Auth::user()->user_type != 'Restaurant')
        {
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }


        $user_email = $request->created_by_email;

        $user = DB::table('users')
            ->select('id')
            ->where('email', '=', $user_email)->first();

        $id = $user->id;
        $restaurant_user_id = $request->restaurant_user_id;

        DB::table('restaurant__users')
            ->where('user_id', $restaurant_user_id)
            ->delete();

        $count = count($request->restaurant_id);

        $rest_id = $request->restaurant_id;

            if($request->password != "")
            {
                $request->validate([
                    'password' => ['required', 'confirmed', Rules\Password::defaults()]
                ]);

                 DB::table('users')
                    ->where('id', $user_id)
                    ->update([
                        'name' => $request->name,
                        'user_type' => $request->user_type,
                        'created_by' => $id,
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60)
                    ]);

                for($i = 0; $i < $count; $i++)
                {
                    $restaurant_id = $rest_id[$i];

                    $creat = Restaurant_User::create([
                        'user_id' => $user_id,
                        'restaurant_id' => $restaurant_id,
                        'user_role' => $request->user_type,
                        'created_by' => $id
                    ]);

                }

            }
            else{

                DB::table('users')
                    ->where('id', $user_id)
                    ->update([
                        'name' => $request->name,
                        'user_type' => $request->user_type,
                        'created_by' => $id
                    ]);

                for($i = 0; $i < $count; $i++)
                {
                    $restaurant_id = $rest_id[$i];

                    Restaurant_User::create([
                        'user_id' => $user_id,
                        'restaurant_id' => $restaurant_id,
                        'user_role' => $request->user_type,
                        'created_by' => $id
                    ]);

                }

            }

            return redirect()->back()
                ->with('success', 'User Updated Successfully.');

    }

    public function delete_user($id)
    {
        if(Auth::user()->user_type != 'Restaurant')
        {
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }

        DB::table('users')->delete($id);
        DB::table('restaurant__users')
            ->where('user_id', $id)
            ->delete();

        return redirect()->back()->with('success', 'User Deleted Successfully.');
        //var_dump($id);
        //die();
    }

	/**
     * Display the specified resource.
     *
     * @param  \App\Models\Restaurant $restaurant
     * @return \Illuminate\Http\Response
     */
    public function show(Restaurant $restaurant)
    {
        //$id = $restaurant->id;

        //return view('Frontend.restaurant_front', compact('restaurant', 'menus'));

        if(Auth::user()->user_type != 'Restaurant')
        {
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }

        $id = $restaurant->id;
        $user_id = Auth::user()->id;

        $promotions = DB::table('promotions')
                    ->where('User_id', Auth::user()->id)
                    ->get();

        $promotions = count($promotions);

        $feedbacks = DB::table('feedback')
                  ->where('published', 'yes')
                  ->where('restaurant_id', $id)
                  ->get();

        $orders = DB::table('orders')
                  ->where('user_id', $user_id)
                  ->where('order_status', 'ordered')
                  ->get();

        $order_menus = DB::table('order_menus')
                 ->where('order_menus.restaurant_id', $id)
                 ->where('order_menus.item_status', 'on')
                 ->get();

        $flip_menus = DB::table('menus')
                 ->where('menus.restaurant_id', $id)
                 ->get();

        $categories = DB::table('categories')
                ->where('categories.restaurant_id', $id)
                ->get();

        return view('Frontend.restaurant', compact('restaurant', 'order_menus', 'flip_menus','feedbacks', 'orders', 'promotions', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Restaurant $restaurant
     * @return \Illuminate\Http\Response
     */
    public function edit(Restaurant $restaurant)
    {
        if(Auth::user()->user_type != 'Restaurant')
        {
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }

        if(Auth::user()->user_type == 'Manager' || Auth::user()->user_type == 'Booking_Manager')
        {
            $info = DB::table('users')
                ->where('id', Auth::user()->id)
                ->first();
            $user_id = $info->created_by;

            $restaurants = DB::table('restaurants')
                ->join('restaurant__users', 'restaurant__users.restaurant_id', '=', 'restaurants.id')
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
        
        $breakfast_hour = DB::table('restaurant_hours')
            ->where('restaurant_id', $restaurant->id)
            ->where('hour_name', '=', 'Breakfast_Hours')
            ->first();

        $lunch_hour = DB::table('restaurant_hours')
            ->where('restaurant_id', $restaurant->id)
            ->where('hour_name', '=', 'Lunch_Hours')
            ->first();

        $dinner_hour = DB::table('restaurant_hours')
            ->where('restaurant_id', $restaurant->id)
            ->where('hour_name', '=', 'Dinner_Hours')
            ->first();

        return view('Admin.Restaurant.edit',compact('restaurant', 'restaurants', 'breakfast_hour',
            'lunch_hour', 'dinner_hour'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Restaurant $restaurant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        $data = $request->validate([
            'Restaurant_name' => 'required|string',
            'Restaurant_phone' => 'required|string',
            'Restaurant_address' => 'required|string',
            'user_id' => 'required',
            'Restaurant_description' => 'required|string|max:500',
            'Restaurant_coordinates' => 'required|string',
            'restaurant_opening_hour' => 'required',
            'restaurant_closing_hour' => 'required',
            'Restaurant_duration' => 'required',
            'Restaurant_max_capacity' => 'required|int',
            'Restaurant_Country' => 'required|string',
            'Restaurant_City' => 'required|string',
            'Restaurant_type' => 'string|max:50',
            'Reservation_update_type' => 'required|string',
            'restaurant_currency' => 'required|string'
        ]);
        
        if($request->breakfast_start_hour != '' || $request->breakfast_end_hour != '')
        {
            if($request->breakfast_start_hour < $request->restaurant_opening_hour)
            {
                return redirect()->back()
                    ->with('error', 'Restaurant breakfast hour not updated, 
                    You cannot define a dinning hour before the restaurant is opened.');
            }
            elseif($request->breakfast_end_hour > $request->restaurant_closing_hour)
            {
                return redirect()->back()
                    ->with('error', 'Restaurant breakfast hour not updated, 
                    You cannot define a dinning hour after the restaurant is closed.');
            }
        }
        if($request->lunch_start_hour != '' || $request->lunch_end_hour != '')
        {
            if($request->lunch_start_hour < $request->restaurant_opening_hour)
            {
                return redirect()->back()
                    ->with('error', 'Restaurant lunch hour not updated, 
                    You cannot define a dinning hour before the restaurant is opened.');
            }
            elseif($request->lunch_end_hour > $request->restaurant_closing_hour)
            {
                return redirect()->back()
                    ->with('error', 'Restaurant lunch hour not updated, 
                    You cannot define a dinning hour after the restaurant is closed.');
            }
        }
        if($request->dinner_start_hour != '' || $request->dinner_end_hour)
        {
            if($request->dinner_start_hour < $request->restaurant_opening_hour)
            {
                return redirect()->back()
                    ->with('error', 'Restaurant dinner hour not updated, 
                    You cannot define a dinning hour before the restaurant is opened.');
            }
            elseif($request->dinner_end_hour > $request->restaurant_closing_hour)
            {
                return redirect()->back()
                    ->with('error', 'Restaurant dinner hour not updated, 
                    You cannot define a dinning hour after the restaurant is closed.');
            }
        }
        
        if(Auth::user()->user_type != 'Restaurant')
        {
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }

        if($request->monday == ""){
            $monday = 'no';
        }
        else{
            $monday = 'yes';
        }
        if($request->tuesday == ""){
            $tuesday = 'no';
        }
        else{
            $tuesday = 'yes';
        }
        if($request->wednesday == ""){
            $wednesday = 'no';
        }
        else{
            $wednesday = 'yes';
        }
        if($request->thursday == ""){
            $thursday = 'no';
        }
        else{
            $thursday = 'yes';
        }
        if($request->friday == ""){
            $friday = 'no';
        }
        else{
            $friday = 'yes';
        }
        if($request->saturday == ""){
            $saturday = 'no';
        }
        else{
            $saturday = 'yes';
        }
        if($request->sunday == ""){
            $sunday = 'no';
        }
        else{
            $sunday = 'yes';
        }

        $restaurant_id = $restaurant->id;

        if($request->Restaurant_photo || $request->Restaurant_logo != "")
        {
            $data = $request->validate([
                'Restaurant_photo' => 'image|mimes:jpeg,png,jpg|max:2048'
            ]);
            if($request->Restaurant_photo != "") {
                $imageName = $request->Restaurant_name . time() . '.' . $request->Restaurant_photo->extension();
                $request->Restaurant_photo->move(public_path('images/restaurant'), $imageName);
            }
            else{
                $imageName = $restaurant->Restaurant_photo;
            }

            if($request->Restaurant_logo != "")
            {
                $request->validate([
                    'Restaurant_logo' => 'image|mimes:jpeg,png,jpg|max:1024'
                ]);
                $logoName = $request->Restaurant_name.time().'.'.$request->Restaurant_logo->extension();
                $request->Restaurant_logo->move(public_path('images/restaurant/logo'), $logoName);

                DB::table('restaurants')
                    ->where('id', $restaurant_id)
                    ->update([
                        'Restaurant_name' => $request->Restaurant_name,
                        'Restaurant_phone' => $request->Restaurant_phone,
                        'Restaurant_address' => $request->Restaurant_address,
                        'Restaurant_hours' => $request->Restaurant_hours,
                        'Restaurant_photo' => $imageName,
                        'Restaurant_logo' => $logoName,
                        'user_id' => $request->user_id,
                        'Restaurant_description' => $request->Restaurant_description,
                        'restaurant_opening_hour' => $request->restaurant_opening_hour,
                        'restaurant_closing_hour' => $request->restaurant_closing_hour,
                        'Restaurant_coordinates' => $request->Restaurant_coordinates,
                        'Restaurant_duration' => $request->Restaurant_duration,
                        'Restaurant_max_capacity' => $request->Restaurant_max_capacity,
                        'Restaurant_price_range' => $request->Restaurant_price_range,
                        'Restaurant_Country' => $request->Restaurant_Country,
                        'Restaurant_City' => $request->Restaurant_City,
                        'Restaurant_type' => $request->Restaurant_type,
                        'restaurant_currency' => $request->restaurant_currency,
                        'Restaurant_color' => $request->Restaurant_color,
                        'monday_open' => $monday,
                        'tuesday_open' => $tuesday,
                        'wednesday_open' => $wednesday,
                        'thursday_open' => $thursday,
                        'friday_open' => $friday,
                        'saturday_open' => $saturday,
                        'sunday_open' => $sunday,
                        'Reservation_update_type' => $request->Reservation_update_type
                    ]);
            }

            DB::table('restaurants')
            ->where('id', $restaurant_id)
            ->update([
                'Restaurant_name' => $request->Restaurant_name,
                'Restaurant_phone' => $request->Restaurant_phone,
                'Restaurant_address' => $request->Restaurant_address,
                'Restaurant_hours' => $request->Restaurant_hours,
                'Restaurant_photo' => $imageName,
                'user_id' => $request->user_id,
                'Restaurant_description' => $request->Restaurant_description,
                'restaurant_opening_hour' => $request->restaurant_opening_hour,
                'restaurant_closing_hour' => $request->restaurant_closing_hour,
                'Restaurant_coordinates' => $request->Restaurant_coordinates,
                'Restaurant_duration' => $request->Restaurant_duration,
                'Restaurant_max_capacity' => $request->Restaurant_max_capacity,
                'Restaurant_price_range' => $request->Restaurant_price_range,
                'Restaurant_Country' => $request->Restaurant_Country,
                'Restaurant_City' => $request->Restaurant_City,
                'Restaurant_type' => $request->Restaurant_type,
                'restaurant_currency' => $request->restaurant_currency,
                'Restaurant_color' => $request->Restaurant_color,
                'monday_open' => $monday,
                'tuesday_open' => $tuesday,
                'wednesday_open' => $wednesday,
                'thursday_open' => $thursday,
                'friday_open' => $friday,
                'saturday_open' => $saturday,
                'sunday_open' => $sunday,
                'Reservation_update_type' => $request->Reservation_update_type
            ]);

        }

        DB::table('restaurants')
            ->where('id', $restaurant_id)
            ->update([
                'Restaurant_name' => $request->Restaurant_name,
                'Restaurant_phone' => $request->Restaurant_phone,
                'Restaurant_address' => $request->Restaurant_address,
                'Restaurant_hours' => $request->Restaurant_hours,
                'user_id' => $request->user_id,
                'Restaurant_description' => $request->Restaurant_description,
                'restaurant_opening_hour' => $request->restaurant_opening_hour,
                'restaurant_closing_hour' => $request->restaurant_closing_hour,
                'Restaurant_coordinates' => $request->Restaurant_coordinates,
                'Restaurant_duration' => $request->Restaurant_duration,
                'Restaurant_max_capacity' => $request->Restaurant_max_capacity,
                'Restaurant_price_range' => $request->Restaurant_price_range,
                'Restaurant_Country' => $request->Restaurant_Country,
                'Restaurant_City' => $request->Restaurant_City,
                'Restaurant_type' => $request->Restaurant_type,
                'restaurant_currency' => $request->restaurant_currency,
                'Restaurant_color' => $request->Restaurant_color,
                'monday_open' => $monday,
                'tuesday_open' => $tuesday,
                'wednesday_open' => $wednesday,
                'thursday_open' => $thursday,
                'friday_open' => $friday,
                'saturday_open' => $saturday,
                'sunday_open' => $sunday,
                'Reservation_update_type' => $request->Reservation_update_type
            ]);
            
        if($request->breakfast_start_hour != '' && $request->breakfast_end_hour)
        {
            if($request->breakfast_start_hour < $request->restaurant_opening_hour)
            {
                return redirect()->back()
                    ->with('error', 'Restaurant breakfast hour not updated, 
                    You cannot define a dinning hour before the restaurant is opened.');
            }
            elseif($request->breakfast_end_hour > $request->restaurant_closing_hour)
            {
                return redirect()->back()
                    ->with('error', 'Restaurant breakfast hour not updated, 
                    You cannot define a dinning hour after the restaurant is closed.');
            }

            if($request->Breakfast_Hour_id != '')
            {
                $Breakfast_Hour_id = $request->Breakfast_Hour_id;
                DB::table('restaurant_hours')
                    ->where('id', $Breakfast_Hour_id)
                    ->update([
                        'start_hour' => $request->breakfast_start_hour,
                        'end_hour' => $request->breakfast_end_hour,
                        'hour_name' => 'Breakfast_Hours',
                        'restaurant_id' => $request->restaurant_id,
                        'restaurant_name' => $request->Restaurant_name
                    ]);
            }
            else{
                $data = new RestaurantHours;

                $data->start_hour = $request->breakfast_start_hour;
                $data->end_hour = $request->breakfast_end_hour;
                $data->hour_name = 'Breakfast_Hours';
                $data->restaurant_id = $restaurant_id;
                $data->restaurant_name = $request->Restaurant_name;

                $data->save();
            }

        }
        if($request->lunch_start_hour != '' && $request->lunch_end_hour != '')
        {
            if($request->lunch_start_hour < $request->restaurant_opening_hour)
            {
                return redirect()->back()
                    ->with('error', 'Restaurant lunch hour not updated, 
                    You cannot define a dinning hour before the restaurant is opened.');
            }
            elseif($request->lunch_end_hour > $request->restaurant_closing_hour)
            {
                return redirect()->back()
                    ->with('error', 'Restaurant lunch hour not updated, 
                    You cannot define a dinning hour after the restaurant is closed.');
            }

            if($request->Lunch_Hour_id != '')
            {
                $Lunch_Hour_id = $request->Lunch_Hour_id;
                DB::table('restaurant_hours')
                    ->where('id', $Lunch_Hour_id)
                    ->update([
                        'start_hour' => $request->lunch_start_hour,
                        'end_hour' => $request->lunch_end_hour,
                        'hour_name' => 'Lunch_Hours',
                        'restaurant_id' => $request->restaurant_id,
                        'restaurant_name' => $request->Restaurant_name
                    ]);
            }
            else{
                $data = new RestaurantHours;

                $data->start_hour = $request->lunch_start_hour;
                $data->end_hour = $request->lunch_end_hour;
                $data->hour_name = 'Lunch_Hours';
                $data->restaurant_id = $restaurant_id;
                $data->restaurant_name = $request->Restaurant_name;

                $data->save();
            }
        }
        if($request->dinner_start_hour != '' && $request->dinner_end_hour)
        {
            if($request->dinner_start_hour < $request->restaurant_opening_hour)
            {
                return redirect()->back()
                    ->with('error', 'Restaurant dinner hour not updated, 
                    You cannot define a dinning hour before the restaurant is opened.');
            }
            elseif($request->dinner_end_hour > $request->restaurant_closing_hour)
            {
                return redirect()->back()
                    ->with('error', 'Restaurant dinner hour not updated, 
                    You cannot define a dinning hour after the restaurant is closed.');
            }

            if($request->Dinner_Hour_id != '')
            {
                $Dinner_Hour_id = $request->Dinner_Hour_id;
                DB::table('restaurant_hours')
                    ->where('id', $Dinner_Hour_id)
                    ->update([
                        'start_hour' => $request->dinner_start_hour,
                        'end_hour' => $request->dinner_end_hour,
                        'hour_name' => "Dinner_Hours",
                        'restaurant_id' => $request->restaurant_id,
                        'restaurant_name' => $request->Restaurant_name
                    ]);
            }
            else{
                $data = new RestaurantHours;

                $data->start_hour = $request->dinner_start_hour;
                $data->end_hour = $request->dinner_end_hour;
                $data->hour_name = 'Dinner_Hours';
                $data->restaurant_id = $restaurant_id;
                $data->restaurant_name = $request->Restaurant_name;

                $data->save();
            }
        }

        return redirect()->route('restaurants.index')
        ->with('success', 'Restaurant Update Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Restaurant $restaurant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurant $restaurant)
    {
        if(Auth::user()->user_type == 'Restaurant' || Auth::user()->user_type == 'Administrator')
        {

            $restaurant->delete();

            /**DB::table('restaurant_galleries')
                ->where('restaurant_id', $restaurant->id)
                ->delete();
                
            DB::table('restaurant_hours')
                ->where('restaurant_id', $restaurant->id)
                ->delete();*/

            return redirect()->back()
                ->with('success','Restaurant deleted successfully');

        }
        else{
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }
    

    }
}
