<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateAircraftLogbookItemsTable
 */
class CreateAircraftLogbookItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aircraft_logbook_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('aircraft_logbook_id')->index();
            $table->dateTimeTz('arrival', $precision = 0);
            $table->unsignedInteger('block_time');
            $table->unsignedSmallInteger('crew');
            $table->dateTimeTz('departure', $precision = 0);
            $table->string('destination');
            $table->text('notes')->nullable()->comment('technical faults etc.');
            $table->string('origin');
            $table->unsignedSmallInteger('pax');
            $table->string('pilot_firstname');
            $table->unsignedBigInteger('pilot_id')->nullable();
            $table->string('pilot_lastname');
            $table->mediumText('pilot_signature')->nullable();
            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();

            $table->foreign('aircraft_logbook_id')->references('id')->on('aircraft_logbooks')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('pilot_id')->references('id')->on('users')
                ->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aircraft_logbook_items', function (Blueprint $table) {
            $table->dropForeign('aircraft_logbook_items_aircraft_logbook_id_foreign');
            $table->dropForeign('aircraft_logbook_items_pilot_id_foreign');
        });

        Schema::dropIfExists('aircraft_logbook_items');
    }
}
