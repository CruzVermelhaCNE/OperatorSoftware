<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCOVID19Cases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covid19_cases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedbigInteger('CODU_number')->nullable();
            $table->unsignedTinyInteger('CODU_localization')->nullable();
            $table->text('activation_mean');
            $table->text('RNU')->nullable();
            $table->text('firstname')->nullable();
            $table->text('lastname')->nullable();
            $table->boolean('sex')->nullable();
            $table->date('DoB')->nullable();
            $table->boolean('suspect')->nullable();
            $table->text('suspect_validation')->nullable();
            $table->boolean('confirmed')->nullable();
            $table->boolean('invasive_care')->nullable();
            $table->text('street')->nullable();
            $table->text('parish')->nullable();
            $table->text('county')->nullable();
            $table->text('district')->nullable();
            $table->text('on_scene_units')->nullable();
            $table->text('source')->nullable();
            $table->float('source_lat')->nullable();
            $table->float('source_long')->nullable();
            $table->text('destination')->nullable();
            $table->float('destination_lat')->nullable();
            $table->float('destination_long')->nullable();
            $table->text('doctor_responsible')->nullable();
            $table->text('driver_name')->nullable();
            $table->text('driver_age')->nullable();
            $table->text('driver_contact')->nullable();
            $table->text('rescuer_name')->nullable();
            $table->text('rescuer_age')->nullable();
            $table->text('rescuer_contact')->nullable();
            $table->dateTime('status_SALOP_activation');
            $table->dateTime('status_AMB_activation')->nullable();
            $table->dateTime('status_base_exit')->nullable();
            $table->dateTime('status_arrival_on_scene')->nullable();
            $table->dateTime('status_departure_from_scene')->nullable();
            $table->dateTime('status_arrival_on_destination')->nullable();
            $table->dateTime('status_departure_from_destination')->nullable();
            $table->dateTime('status_base_return')->nullable();
            $table->dateTime('status_available')->nullable();
            $table->float('total_distance')->nullable();
            $table->text('structure')->nullable();
            $table->text('vehicle_identification')->nullable();
            $table->unsignedTinyInteger('vehicle_type')->nullable();
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
        Schema::dropIfExists('covid19_cases');
    }
}
