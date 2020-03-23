<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCOVID19AmbulanceTeam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covid19_ambulance_team_members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ambulance_id');
            $table->bigInteger('case_id');
            $table->text('name')->nullable();
            $table->text('age')->nullable();
            $table->text('contact')->nullable();
            $table->text('type');
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
        Schema::dropIfExists('covid19_ambulance_team_members');
    }
}
