<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCanceledTimingsToTheaterOfOperationsEventVictim extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('theater_of_operations_event_victims', function (Blueprint $table) {
            $table->dateTime('canceled_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('theater_of_operations_event_victims', function (Blueprint $table) {
            $table->dropColumn('canceled_at');
        });
    }
}
