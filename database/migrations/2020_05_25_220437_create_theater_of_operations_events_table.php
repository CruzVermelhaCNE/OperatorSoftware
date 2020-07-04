<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheaterOfOperationsEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theater_of_operations_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('theater_of_operations_id')->nullable();
            $table->unsignedBigInteger('theater_of_operations_sector_id')->nullable();
            $table->text('codu')->nullable();
            $table->text('cdos')->nullable();
            $table->text('type');
            $table->unsignedTinyInteger('status')->default(1);
            $table->longText('observations');
            $table->text('location');
            $table->decimal("lat",11,8);
            $table->decimal("long",11,8);
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
        Schema::dropIfExists('theater_of_operations_events');
    }
}
