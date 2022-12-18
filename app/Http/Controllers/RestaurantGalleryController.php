<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Restaurant_gallery;

class RestaurantGalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $restaurants = DB::table('restaurants')
            ->where('user_id', '=', Auth::user()->id)
            ->get();

        $images = DB::table('restaurants')
            ->join('restaurant_galleries', 'restaurant_galleries.restaurant_id', '=', 'restaurants.id')
            ->select('restaurant_galleries.id', 'restaurant_galleries.Restaurant_name', 'restaurant_galleries.restaurant_image_path',
                'restaurant_galleries.owner_id', 'restaurant_galleries.restaurant_id')
            ->where('restaurants.user_id', '=', Auth::user()->id)
            ->get();

        return view('Admin.Restaurant.gallery', compact('restaurants', 'images'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|int',
            'Restaurant_photo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'created_by' => 'required'
        ]);
        $images = DB::table('restaurant_galleries')
            ->where('restaurant_id', $request->restaurant_id)
            ->get();
        $count_images = count($images);
        if ($count_images >= 4)
        {
            return redirect()->back()
                ->with('error', "Image not added, You can't add more than 4 images for a single Restaurant gallery.");
        }
        else{
            $Rest_name = DB::table('restaurants')
                ->select('Restaurant_name', 'user_id')
                ->where('id', $request->restaurant_id)
                ->first();

            $Restaurant_name = $Rest_name->Restaurant_name;
            $owner_id = $Rest_name->user_id;
            $imageName = $Restaurant_name.time().'.'.$request->Restaurant_photo->extension();
            $request->Restaurant_photo->move(public_path('images/gallery'), $imageName);

            $info = new Restaurant_gallery;
            $info->Restaurant_name = $Restaurant_name;
            $info->restaurant_id = $request->restaurant_id;
            $info->restaurant_image_path = $imageName;
            $info->created_by = $request->created_by;
            $info->owner_id = $owner_id;

            $info->save();

            return redirect()->back()
                ->with('success', 'Image added Successfully.');
        }

    }
}
