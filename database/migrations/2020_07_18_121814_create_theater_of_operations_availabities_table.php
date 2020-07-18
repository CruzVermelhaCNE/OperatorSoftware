<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheaterOfOperationsAvailabitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theater_of_operations_availabities', function (Blueprint $table) {
            $table->id();
            $table->text('type');
            $table->text('plate');
            $table->text('tail_number');
            $table->text('structure');
            $table->decimal('lat', 11, 8);
            $table->decimal('long', 11, 8);
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
        Schema::dropIfExists('theater_of_operations_availabities');
    }
}
