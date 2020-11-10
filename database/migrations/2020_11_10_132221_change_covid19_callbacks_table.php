<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCovid19CallbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('covid19_callbacks', function (Blueprint $table) {
            $table->unsignedBigInteger('called_back_user_id')->nullable()->change();
            $table->boolean('called_back')->default(false);
            $table->text('number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('covid19_callbacks', function (Blueprint $table) {
            $table->unsignedBigInteger('called_back_user_id')->change();
            $table->dropColumn('called_back');
            $table->dropColumn('number');
        });
    }
}
