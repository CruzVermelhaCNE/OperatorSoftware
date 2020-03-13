<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferenceAndResponsibleDoctorsToCases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('covid19_cases', function (Blueprint $table) {
            $table->text('ref')->nullable();
            $table->text('doctor_responsible_on_scene')->nullable();
            $table->text('doctor_responsible_on_destination')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('covid19_cases', function (Blueprint $table) {
            $table->dropColumn('doctor_responsible_on_scene');
            $table->dropColumn('doctor_responsible_on_destination');
            $table->dropColumn('ref');
            $table->text('doctor_responsible')->nullable();
        });
    }
}
