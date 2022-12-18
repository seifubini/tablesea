<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant_gallery extends Model
{
    use HasFactory;

    protected $fillable = ['Restaurant_name', 'restaurant_id', 'restaurant_image_path', 'created_by', 'owner_id'];
}
