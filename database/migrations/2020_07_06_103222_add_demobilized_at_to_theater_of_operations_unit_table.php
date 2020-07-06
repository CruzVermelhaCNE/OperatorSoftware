<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDemobilizedAtToTheaterOfOperationsUnitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('theater_of_operations_units', function (Blueprint $table) {
            $table->dateTime('demobilized_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('theater_of_operations_units', function (Blueprint $table) {
            $table->dropColumn('demobilized_at');
        });
    }
}
