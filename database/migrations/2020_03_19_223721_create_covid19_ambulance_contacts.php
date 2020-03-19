<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCOVID19AmbulanceContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covid19_ambulance_contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ambulance_id');
            $table->bigInteger('contact');
            $table->text('name');
            $table->boolean('sms');
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
        Schema::dropIfExists('covid19_ambulance_contacts');
    }
}
