<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheaterOfOperationsTimeTapesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theater_of_operations_time_tapes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('theater_of_operations_id')->nullable();
            $table->unsignedBigInteger('theater_of_operations_sector_id')->nullable();
            $table->unsignedTinyInteger('type');
            $table->dateTime('date');
            $table->text('description');
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
        Schema::dropIfExists('theater_of_operations_time_tapes');
    }
}
