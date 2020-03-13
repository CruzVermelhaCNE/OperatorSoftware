<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCOVID19Ambulances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covid19_ambulances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('status');
            $table->dateTime('status_date');
            $table->dateTime('predicted_base_exit')->nullable();
            $table->dateTime('predicted_arrival_on_scene')->nullable();
            $table->dateTime('predicted_departure_from_scene')->nullable();
            $table->dateTime('predicted_arrival_on_destination')->nullable();
            $table->dateTime('predicted_departure_from_destination')->nullable();
            $table->dateTime('predicted_base_return')->nullable();
            $table->dateTime('predicted_available')->nullable();
            $table->float('base_lat');
            $table->float('base_long');
            $table->text('vehicle_identification');
            $table->text('structure');
            $table->bigInteger('case_id')->nullable();
            $table->bigInteger('updated_by');
            $table->boolean('active_prevention');
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
        Schema::dropIfExists('covid19_ambulances');
    }
}
