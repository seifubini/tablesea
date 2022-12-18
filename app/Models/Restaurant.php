<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
    	'Restaurant_email', 'Restaurant_name', 'Restaurant_phone', 'Restaurant_photo', 'Restaurant_address',
        'Restaurant_hours', 'Restaurant_slug', 'Restaurant_logo', 'Restaurant_coordinates', 'user_id',
        'Restaurant_description', 'Restaurant_type', 'Restaurant_Country', 'Restaurant_City','featured',
        'monday_open', 'tuesday_open', 'wednesday_open', 'thursday_open', 'friday_open', 'saturday_open', 'sunday_open',
        'restaurant_opening_hour', 'restaurant_closing_hour', 'Restaurant_duration', 'Restaurant_max_capacity',
        'booking_manager_id', 'manager_id', 'Restaurant_price_range', 'Reservation_update_type', 'restaurant_currency', 'Restaurant_color'
    ];

    /*
     * Restaurants belong to a user
     *
    **/
    public function user(){

    	return $this->BelongsTo(User::class);
    }


    /**
     * Get the menus for the restaurant.
     */
    public function menu()
    {
        return $this->hasMany(menu::class);
    }

    /**
     * Get the order menus for the restaurant.
     */
    public function order_menu()
    {
        return $this->hasMany(order_menu::class);
    }
}


