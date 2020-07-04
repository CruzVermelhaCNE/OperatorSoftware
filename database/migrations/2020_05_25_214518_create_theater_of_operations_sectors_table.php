<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheaterOfOperationsSectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theater_of_operations_sectors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('theater_of_operations_id');
            $table->text('name');
            $table->longText('observations');
            $table->text('location');
            $table->decimal("lat",11,8);
            $table->decimal("long",11,8);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theater_of_operations_sectors');
    }
}
