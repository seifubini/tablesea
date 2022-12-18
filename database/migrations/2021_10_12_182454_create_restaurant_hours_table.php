<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_hours', function (Blueprint $table) {
            $table->id();
            $table->time('start_hour');
            $table->time('end_hour');
            $table->string('hour_name');
            $table->string('restaurant_id');
            $table->string('restaurant_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_hours');
    }
}
