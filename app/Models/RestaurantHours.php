<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantHours extends Model
{
    use HasFactory;

    protected $fillable = ['start_hour', 'end_hour', 'hour_name', 'restaurant_id', 'restaurant_name'];

    /*
     * Restaurant users belong to a Restaurant
     *
    **/
    public function Restaurant(){

        return $this->BelongsTo(Restaurant::class);
    }
}
