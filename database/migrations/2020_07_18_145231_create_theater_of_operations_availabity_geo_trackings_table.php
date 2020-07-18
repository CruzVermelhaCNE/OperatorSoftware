<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheaterOfOperationsAvailabityGeoTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theater_of_operations_availabity_geo_trackings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('theater_of_operations_availabities_id');
            $table->text('system');
            $table->text('external_id');
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
        Schema::dropIfExists('theater_of_operations_availabity_geo_trackings');
    }
}
