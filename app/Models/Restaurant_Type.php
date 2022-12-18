<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant_Type extends Model
{
    use HasFactory;

    protected $fillable = [
    	'Restaurant_Type_Name', 'Restaurant_Type_Photo'
    ];
}
