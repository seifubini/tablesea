<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_code', 'date_of_reservation', 'time_of_reservation', 'number_of_people', 'table_id',
        'table_indentifier', 'user_id', 'user_name', 'user_email', 'user_phone','reserver_message', 'reservation_status', 'reservation_type',
        'reservation_tag', 'reservation_duration', 'restaurant_id', 'reservation_total_cost', 'hostess_note', 'reservation_attachment'
    ];

    public function Reservation()
    {
        return $this->belongsTo(Table::class);
    }

}
