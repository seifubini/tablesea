<?php

namespace App\Http\Controllers;

use App\Models\Restaurant_gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Report;
use App\Models\Restaurant_Type;

class AdminController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Display All Users
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$users = DB::table('users')
            ->where('user_type', '!=', 'Client')
            ->get();

        $user_id = Auth::user()->id;

        $restaurants = DB::table('restaurants')
            ->where('user_id', $user_id)
            ->get();

        $restaurant_id = "";

		return view('Admin.Administrator.index', compact('users', 'restaurants', 'restaurant_id'))
		->with('i', (request()->input('page', 1) - 1) * 5);
	}

    public function clients()
    {
        $clients = DB::table('users')
            ->where('user_type', 'Client')
            ->get();

        $user_id = Auth::user()->id;

        $restaurants = DB::table('restaurants')
            ->where('user_id', $user_id)
            ->get();

        $restaurant_id = "";

        return view('Admin.Administrator.clients', compact('clients', 'restaurants', 'restaurant_id'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

	public function create()
	{
        $user_id = Auth::user()->id;

        $restaurants = DB::table('restaurants')
            ->where('user_id', $user_id)
            ->get();
		return view('Admin.Administrator.create', compact('restaurants'));
	}

    public function add_type()
    {
        $user_id = Auth::user()->id;

        $restaurants = DB::table('restaurants')
            ->where('user_id', $user_id)
            ->get();
        return view('Admin.Administrator.add_type', compact('restaurants'));
    }

    public function store_type(Request $request)
    {
        $data = $request->validate([
            'Restaurant_Type_Name' => 'required|string',
            'Restaurant_Type_Photo' => 'required|image|mimes:jpeg,png,jpg|max:4096'
        ]);

        $imageName = $request->Restaurant_Type_Name.time().'.'.$request->Restaurant_Type_Photo->extension();
        $request->Restaurant_Type_Photo->move(public_path('images/Restaurant_Type'), $imageName);

        $info = new Restaurant_Type;

        $info->Restaurant_Type_Name = $request->Restaurant_Type_Name;
        $info->Restaurant_Type_Photo = $imageName;

        $info->save();

        return redirect()->route('restaurant_type')
        ->with('success', 'Restaurant Type Created Successfully.');
    }

    public function restaurant_type()
    {
        $user_id = Auth::user()->id;

        $restaurants = DB::table('restaurants')
            ->where('user_id', $user_id)
            ->get();
        $types = Restaurant_Type::All();

        return view('Admin.Administrator.restaurant_type',compact('types', 'restaurants'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

	/**
     * Store a new user in database
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\Response
     */
	public function store(Request $request)
	{
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_type' => 'required',
            'no_of_restaurants' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'no_of_restaurants' => $request->no_of_restaurants
        ]);

        event(new Registered($user));

        return redirect()->back()
        ->with('success', 'User Created Successfully.');
	}

    /**
     * Report the specified resource to Administrator.
     *
     * @param  \App\Models\$id
     * @return \Illuminate\Http\Response
     */
    public function report($id)
    {
        $user_id = Auth::user()->id;

        $restaurants = DB::table('restaurants')
            ->where('user_id', $user_id)
            ->get();

        $feedback = DB::table('feedback')
                    ->select('feedback_message')
                    ->where('id', $id)
                    ->first();
        $feedbacks = DB::table('feedback')
                    ->select('restaurant_id')
                    ->where('id', $id)
                    ->first();
        $restaurant_id = $feedbacks->restaurant_id;
        $feedback_message = $feedback->feedback_message;
        //echo $feedback_message;
        //die();
        $restaurant = DB::table('restaurants')
                      ->select('restaurant_name')
                      ->where('id', $restaurant_id)
                      ->first();
        $restaurant_name = $restaurant->restaurant_name;

        Report::create([
            'feedback_id' => $id,
            'feedback_message' => $feedback_message,
            'restaurant_name' => $restaurant_name
        ]);

        return redirect()->back()->with('success', 'Feedback reported successfully!');
    }

    public function manage_restaurants()
    {
        //$restaurants = Restaurant::All();

        $restaurants = DB::table('restaurants')
            ->join('users', 'users.id', '=', 'restaurants.user_id')
            ->select('restaurants.*', 'users.no_of_restaurants', 'users.email')
            ->where('users.user_type', 'Restaurant')
            ->orWhere('users.user_type', 'Hotel')
            ->get();

        return view('Admin.Administrator.restaurants', compact('restaurants'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function update_restaurant(Request $request)
    {
        $data = $request->validate([
            'restaurant_id' => 'required|int',
            'email' => 'required|email',
            'featured' => 'required|string',
            'no_of_restaurants' => 'required|int'
        ]);

        $restaurant_id = $request->restaurant_id;

        $email = $request->email;

        DB::table('restaurants')
            ->where('id', $restaurant_id)
            ->update([
                'featured' => $request->featured
            ]);

        DB::table('users')
            ->where('email', $email)
            ->update([
                'no_of_restaurants' => $request->no_of_restaurants
            ]);

        return redirect()->back()->with('success', 'Restaurant user updated successfully.');
    }

    public function get_restaurant_users($id)
    {
        $restaurant_users = DB::table('users')
            ->join('restaurant__users', 'restaurant__users.user_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'users.email', 'users.user_type', 
                'users.user_image', 'users.created_at')
            ->where('restaurant__users.restaurant_id', $id)
            ->get();

        return json_encode($restaurant_users);   
    }

    public function make_featured($id)
    {
        $status = DB::table('restaurants')
                  ->select('featured')
                  ->where('id', $id)
                  ->first();

        $value = $status->featured;

        if($value == 'no')
        {
            DB::table('restaurants')
            ->where('id', $id)
            ->update(['featured' => 'yes']);
        }
        else{
            DB::table('restaurants')
            ->where('id', $id)
            ->update(['featured' => 'no']);
        }

        return redirect()->route('manage_restaurants')
        ->with('success', 'Restaurant Updated Successfully.');
        //var_dump($value);
        //die();
    }

    public function my_reports()
    {
        $user_id = Auth::user()->id;

        $restaurants = DB::table('restaurants')
            ->where('user_id', $user_id)
            ->get();

        $reports = Report::All();

        return view('Admin.Administrator.report', compact('reports', 'restaurants'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Remove the Restaurant from system.
     *
     * @param  \App\Models\$id
     * @return \Illuminate\Http\Response
     */
    public function delete_restaurant($id)
    {
        Restaurant::where('id', $id)->delete();

        return redirect()->back()
            ->with('success','Restaurant deleted successfully');

    }

	/**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\$id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        $user_type = $user->user_type;

        if($user_type == "Restaurant")
        {

            Restaurant::where('user_id', $id)->delete();
            User::where('id', $id)->delete();
            Restaurant_gallery::where('owner_id', $id)->delete();

            return redirect()->route('administrator.index')
                ->with('success','User and Restaurants owned by user deleted successfully');
        }
        else{
            User::where('id', $id)->delete();

            return redirect()->route('administrator.index')
                ->with('success','User deleted successfully');
        }

    }
}
