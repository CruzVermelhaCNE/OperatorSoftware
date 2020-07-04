<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUnitReferenceOnTheaterOfOperationsEventVictims extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('theater_of_operations_event_victims', function (Blueprint $table) {
            $table->dropColumn('theater_of_operations_unit_id');
            $table->unsignedBigInteger('theater_of_operations_event_unit_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('theater_of_operations_event_victims', function (Blueprint $table) {
            $table->dropColumn('theater_of_operations_event_unit_id');
            $table->unsignedBigInteger('theater_of_operations_unit_id')->nullable();
        });
    }
}
