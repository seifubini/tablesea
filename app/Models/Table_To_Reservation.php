<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table_To_Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['reservation_code', 'table_id'];

    public function Table_To_Reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

}
