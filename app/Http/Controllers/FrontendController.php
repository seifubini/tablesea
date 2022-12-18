<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Feedback;
use App\Models\promotion;
use App\Models\Category;
use App\Models\Restaurant_Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FrontendController extends Controller
{

    public function search_restaurant(Request $request)
    {

        $query = $request->search_query;
        $filter_name = Restaurant::where('Restaurant_name', 'LIKE', '%'. $query. '%')->first();
        $restaurant_types = Restaurant_Type::All();

        if($filter_name != "")
        {
          $id = $filter_name->id;

          return redirect()->route('show_restaurants', $id);

        }
        elseif($filter_name == "")
        {
          $restaurants = Restaurant::where('Restaurant_address', 'LIKE', '%'. $query. '%')->get();

          return view('Frontend.by_location', compact('restaurants', 'query', 'restaurant_types'));

        }
        else
        {
          return redirect()->back()->with('error', 'Nothing Found in our System.');
        }
    }

    /**
     * Display Restaurants by location
     *
     * @return \Illuminate\Http\Response
     */
    public function by_type($key)
    {

        $query = "";
        $restaurant_type = $key;
        $restaurants = DB::table('restaurants')
                       ->join('restaurant__types', 'restaurants.restaurant_type', '=', 'restaurant__types.id')
                       ->where('restaurant__types.Restaurant_Type_Name', $key)
                       ->get();

        //var_dump($restaurants);
        //die();

        return view('Frontend.by_restaurant_type', compact('restaurants', 'query', 'restaurant_type'));
    }

    public function sort_by($value)
    {
        var_dump($value);
        die();

        $query = "";
        $restaurant_type = $value;
        $restaurants = DB::table('restaurants')
                       ->where('Restaurant_type', $restaurant_type)
                       ->get();

        return view('Frontend.by_restaurant_type', compact('restaurants', 'query', 'restaurant_type'));
    }

    public function set_modal($id){

      $order_menu = $order_menu = DB::table('order_menus')
                ->where('id', $id)
                ->first();

      echo json_encode($order_menu);
    }

    public function restaurants($id)
    {
    	$id = $id;

      $query = "";

        $feedbacks = DB::table('feedback')
                  ->where('published', 'yes')
                  ->where('restaurant_id', $id)
                  ->get();
        $feedback_count = count($feedbacks);
        $order_menus = DB::table('order_menus')
                 ->where('order_menus.restaurant_id', $id)
                 ->where('order_menus.item_status', 'on')
                 ->get();

        $restaurant = DB::table('restaurants')->find($id);

        $flip_menus = DB::table('menus')
                 ->where('menus.restaurant_id', $id)
                 ->get();

        $categories = DB::table('categories')
                ->where('categories.restaurant_id', $id)
                ->get();

        if(Auth::check()){
        	$user_id = Auth::user()->id;
        	$promotions = DB::table('promotions')
                    ->where('User_id', Auth::user()->id)
                    ->get();
            $promotion_count = count($promotions);
            $orders = DB::table('orders')
                  ->where('user_id', $user_id)
                  ->where('order_status', 'ordered')
                  ->get();

            //var_dump($categories);
            //die();
            return view('Frontend.restaurant', compact('restaurant', 'order_menus', 'orders', 'promotions', 'promotion_count','feedback_count', 'flip_menus','feedbacks', 'categories', 'query'));
        }

        return view('Frontend.restaurant', compact('restaurant', 'order_menus', 'flip_menus','feedbacks', 'feedback_count', 'categories', 'query'));
    }

    public function show(Restaurant $restaurant)
    {
    	$id = $restaurant->id;

        $feedbacks = DB::table('feedback')
                  ->where('published', 'yes')
                  ->where('restaurant_id', $id)
                  ->get();
        $order_menus = DB::table('order_menus')
                 ->where('order_menus.restaurant_id', $id)
                 ->where('order_menus.item_status', 'on')
                 ->get();

        //$restaurant = DB::table('restaurants')->find($id);

        $flip_menus = DB::table('menus')
                 ->where('menus.restaurant_id', $id)
                 ->get();

        $query = "";

        $categories = DB::table('categories')
                ->where('categories.restaurant_id', $id)
                ->get();
        if(Auth::check()){
        	$user_id = Auth::user()->id;
        	$promotions = DB::table('promotions')
                    ->where('User_id', Auth::user()->id)
                    ->get();
            $promotion_count = count($promotions);
            $orders = DB::table('orders')
                  ->where('user_id', $user_id)
                  ->where('order_status', 'ordered')
                  ->get();

            //var_dump($id);
            //die();
            return view('Frontend.restaurant', compact('restaurant', 'order_menus', 'orders', 'promotions', 'promotion_count', 'flip_menus','feedbacks', 'categories', 'query'));
        }

        return view('Frontend.restaurant', compact('restaurant', 'order_menus', 'flip_menus','feedbacks', 'categories', 'query'));
    }
}
