<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_name', 'table_price', 'table_shape', 'min_covers', 'max_covers', 'restaurant_id', 'table_status',
        'table_is_booked', 'table_order', 'table_position'
    ];

    /**
     * Get the order_menus for the category.
     */
    public function Reservation()
    {
        return $this->hasMany(Reservation::class);
    }

    public function Table_To_Reservation()
    {
        return $this->hasMany(Table_To_Reservation::class);
    }
}
