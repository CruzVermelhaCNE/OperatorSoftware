<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheaterOfOperationsEventVictimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theater_of_operations_event_victims', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('theater_of_operations_event_id');
            $table->unsignedBigInteger('theater_of_operations_unit_id')->nullable();
            $table->text('name')->nullable();
            $table->decimal('age', 3, 0)->nullable();
            $table->boolean('sex')->nullable();
            $table->decimal('sns', 9, 0)->nullable();
            $table->unsignedTinyInteger('status');
            $table->dateTime('departure_from_scene')->nullable();
            $table->dateTime('arrival_on_destination')->nullable();
            $table->dateTime('assisted_on_scene')->nullable();
            $table->dateTime('abandoned_scene')->nullable();
            $table->dateTime('refused_assistance')->nullable();
            $table->longText('observations')->nullable();
            $table->text('destination')->nullable();
            $table->decimal('destination_lat', 11, 8)->nullable();
            $table->decimal('destination_long', 11, 8)->nullable();
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
        Schema::dropIfExists('theater_of_operations_event_victims');
    }
}
