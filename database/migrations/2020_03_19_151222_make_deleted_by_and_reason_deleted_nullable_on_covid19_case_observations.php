<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeDeletedByAndReasonDeletedNullableOnCOVID19CaseObservations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('covid19_case_observations', function (Blueprint $table) {
            $table->bigInteger('deleted_by')->nullable()->change();
            $table->text('reason_deleted')->nullable()->change();
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('covid19_case_observations', function (Blueprint $table) {
            $table->bigInteger('deleted_by')->change();
            $table->text('reason_deleted')->change();
        });
    }
}
