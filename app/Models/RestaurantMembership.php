<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantMembership extends Model
{
    use HasFactory;

    protected $fillable = ['membership_name', 'Restaurant_name', 'restaurant_id'];
}
