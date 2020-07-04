<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheaterOfOperationsUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theater_of_operations_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('theater_of_operations_id')->nullable();
            $table->unsignedBigInteger('theater_of_operations_sector_id')->nullable();
            $table->unsignedBigInteger('theater_of_operations_poi_id')->nullable();
            $table->text('type');
            $table->text('plate')->nullable();
            $table->text('tail_number')->nullable();
            $table->longText('observations');
            $table->text('structure');
            $table->decimal('base_lat', 11, 8)->nullable();
            $table->decimal('base_long', 11, 8)->nullable();
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
        Schema::dropIfExists('theater_of_operations_units');
    }
}
