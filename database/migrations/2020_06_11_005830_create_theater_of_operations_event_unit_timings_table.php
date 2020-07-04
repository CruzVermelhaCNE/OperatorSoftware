<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheaterOfOperationsEventUnitTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theater_of_operations_event_unit_timings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('theater_of_operations_event_unit_id')->nullable();
            $table->dateTime('activation');
            $table->dateTime('on_way_to_scene')->nullable();
            $table->dateTime('arrival_on_scene')->nullable();
            $table->dateTime('departure_from_scene')->nullable();
            $table->dateTime('arrival_on_destination')->nullable();
            $table->dateTime('departure_from_destination')->nullable();
            $table->dateTime('available')->nullable();
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
        Schema::dropIfExists('theater_of_operations_event_unit_timings');
    }
}
