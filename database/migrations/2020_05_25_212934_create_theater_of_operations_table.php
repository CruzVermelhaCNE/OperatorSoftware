<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheaterOfOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theater_of_operations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name');
            $table->text('type');
            $table->text('creation_channel');
            $table->text('level');
            $table->text('cdos')->nullable();
            $table->text('location');
            $table->longText('observations');
            $table->decimal('lat', 11, 8);
            $table->decimal('long', 11, 8);
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
        Schema::dropIfExists('theater_of_operations');
    }
}
