<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'user_name', 'restaurant_id', 'restaurant_name', 'user_email', 
        'subscription_code', 'subscription_start_date', 'subscription_expire_date', 'number_of_days', 
        'created_by', 'updated_by', 'subscription_status'
    ];

    /*
     * Packages belong to a user
     *
    **/
    public function user(){
        
        return $this->BelongsTo(User::class);
    }
}
