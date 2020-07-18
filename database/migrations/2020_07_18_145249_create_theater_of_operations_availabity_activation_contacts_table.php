<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheaterOfOperationsAvailabityActivationContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theater_of_operations_availabity_activation_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('theater_of_operations_availabities_id');
            $table->text('name');
            $table->decimal('contact', 9, 0);
            $table->boolean('auto_notify')->default(false);
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
        Schema::dropIfExists('theater_of_operations_availabity_activation_contacts');
    }
}
