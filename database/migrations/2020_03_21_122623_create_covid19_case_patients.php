<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCOVID19AmbulancePatient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covid19_case_patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('case_id');
            $table->text('RNU')->nullable();
            $table->text('firstname')->nullable();
            $table->text('lastname')->nullable();
            $table->boolean('sex')->nullable();
            $table->date('DoB')->nullable();
            $table->boolean('suspect')->nullable();
            $table->text('suspect_validation')->nullable();
            $table->boolean('confirmed')->nullable();
            $table->boolean('invasive_care')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('covid19_case_patients');
    }
}
