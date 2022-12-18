<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestType extends Model
{
    use HasFactory;

    protected $fillable = ['guest_type_name', 'Restaurant_name', 'restaurant_id'];
}
