<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('user_name');
            $table->string('restaurant_id');
            $table->string('restaurant_name');
            $table->string('user_email');
            $table->string('subscription_code');
            $table->string('subscription_start_date');
            $table->string('subscription_expire_date');
            $table->string('number_of_days');
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('subscription_status');
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
        Schema::dropIfExists('packages');
    }
}
