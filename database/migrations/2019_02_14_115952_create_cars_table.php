<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->increments('car_id');
            $table->unsignedInteger('user_id');
            $table->string('category');
            $table->string('model');
            $table->string('motor_power');
            $table->tinyInteger('ports');
            $table->string('board');
            $table->string('type_vehicle');
            $table->string('year', 4);
            $table->string('fuel', 13);
            $table->integer('mileage');
            $table->string('exchange', 15);
            $table->string('direction', 12);
            $table->string('color', 20);
            $table->string('options')->default('');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
