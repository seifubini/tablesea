<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant_User extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'restaurant_id', 'user_role', 'created_by'];

    /*
     * Restaurant users belong to a Restaurant
     *
    **/
    public function Restaurant(){

        return $this->BelongsTo(Restaurant::class);
    }
}
