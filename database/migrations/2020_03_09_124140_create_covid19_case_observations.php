<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCOVID19CaseObservations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covid19_case_observations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('case_id');
            $table->dateTime('date');
            $table->longText('observation');
            $table->bigInteger('author_id');
            $table->bigInteger('deleted_by');
            $table->text('reason_deleted');
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
        Schema::dropIfExists('covid19_case_observations');
    }
}
