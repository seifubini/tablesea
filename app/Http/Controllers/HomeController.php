<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Feedback;
use App\Models\promotion;
use App\Models\Category;
use App\Models\order_menu;
use App\Models\menu;
use App\Models\Restaurant_Type;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurants = Restaurant::All();

        return view('Frontend.index', compact('restaurants'));
    }

    /**
     * Display Restaurants by location
     *
     * @return \Illuminate\Http\Response
     */
    public function location()
    {
        $query = "";
        $rest_id = "";
        $restaurants = DB::table('restaurants')
                       ->orderBy('created_at', 'desc')
                       ->get();
        //Restaurant::All()->sortBy('created_at', 'desc');
        $restaurant_types = Restaurant_Type::All();

        return view('Frontend.by_location', compact('restaurants', 'query', 'restaurant_types', 'rest_id'));
    }

    public function by_popularity()
    {
        $query = "";
        $rest_id = "";
        $restaurants = DB::table('restaurants')
                       ->join('orders', 'restaurants.id', '=', 'orders.restaurant_id')
                       ->orderBy('created_at', 'desc')
                       ->get();
        //Restaurant::All()->sortBy('created_at', 'desc');
        $restaurant_types = Restaurant_Type::All();

        return view('Frontend.by_location', compact('restaurants', 'query', 'restaurant_types', 'rest_id'));
    }

    /**
     * Display Restaurants by location
     *
     * @return \Illuminate\Http\Response
     */
    public function by_type($key)
    {
        var_dump($key);
        die();

        $query = "";
        $restaurant_type = $key;
        $restaurants = DB::table('restaurants')
                       ->where('Restaurant_type', $key)
                       ->get();

        return view('Frontend.by_restaurant_type', compact('restaurants', 'query', 'restaurant_type'));
    }

    /**
     * Display a Restaurant and its menus
     *
     * @return \Illuminate\Http\Response
     */
    public function restaurants($id)
    {
        $id = $id;

        $query = "";

        $feedbacks = DB::table('feedback')
                  ->where('published', 'yes')
                  ->where('restaurant_id', $id)
                  ->get();

        $order_menus = DB::table('order_menus')
                 ->where('order_menus.restaurant_id', $id)
                 ->where('order_menus.item_status', 'on')
                 ->get();

        $flip_menus = DB::table('menus')
                 ->where('menus.restaurant_id', $id)
                 ->get();

        $restaurant = DB::table('restaurants')
                      ->where('id', $id)
                      ->first();

        $rest_id = $id;

        $categories = DB::table('categories')
                ->where('categories.restaurant_id', $id)
                ->get();

        $category_menus = DB::table('categories')
                    ->join('order_menus', 'categories.id', '=', 'order_menus.category_id')
                    ->select('order_menus.category_name', 'order_menus.item_name', 'order_menus.item_price', 'order_menus.menu_photo',
                        'order_menus.menu_type')
                    ->where('categories.restaurant_id', '=', $id)
                    ->get();
        $sub_categories = DB::table('sub_categories')
                          ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
                          ->join('order_menus', 'sub_categories.id', '=', 'order_menus.sub_category_id')
                          ->where('sub_categories.restaurant_id', $id)
                          ->get();

        $product = Category::with('order_menu')->get();
        //var_dump($sub_categories);
        //die();

        $feedback_count = count($feedbacks);

        return view('Frontend.restaurant', compact('restaurant', 'product', 'category_menus' ,'order_menus', 'flip_menus','feedbacks', 'categories', 'feedback_count', 'sub_categories', 'query', 'rest_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function show(Restaurant $restaurant)
    {
        $id = $restaurant->id;
        
        $menus = DB::table('order_menus')
                 ->where('order_menus.restaurant_id', $id)
                 //->where('order_menus.item_status', 'on')
                 ->get();

        //var_dump($id);
        //die();

        return view('Frontend.order_restaurant', compact('restaurant', 'menus'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
