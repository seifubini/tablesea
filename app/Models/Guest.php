<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Guest extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['user_name', 'email', 'phone_number', 'user_tag', 'restaurant_id', 'guest_note', 'guest_title', 'membership_tag'];
}
