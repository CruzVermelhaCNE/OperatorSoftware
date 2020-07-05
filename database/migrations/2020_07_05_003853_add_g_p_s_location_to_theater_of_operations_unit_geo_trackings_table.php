<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGPSLocationToTheaterOfOperationsUnitGeoTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('theater_of_operations_unit_geo_trackings', function (Blueprint $table) {
            $table->decimal('lat', 11, 8)->nullable();
            $table->decimal('long', 11, 8)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('theater_of_operations_unit_geo_trackings', function (Blueprint $table) {
            $table->dropColumn('lat');
            $table->dropColumn('long');
        });
    }
}
