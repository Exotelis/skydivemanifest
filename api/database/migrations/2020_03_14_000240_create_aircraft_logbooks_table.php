<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateAircraftLogbooksTable
 */
class CreateAircraftLogbooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aircraft_logbooks', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('aircraft_registration')->unique();
            $table->integer('transfer')->default(0)->comment('Transfer from old logbook');
            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();

            $table->foreign('aircraft_registration')->references('registration')->on('aircraft')
                ->onDelete('cascade')->onUpdate('cascade');
        });

        // Add aircraft logbook items permissions
        Permission::insert([
            ['slug' => 'aircraft_logbook:delete', 'name' => 'Delete aircraft logbook entries',],
            ['slug' => 'aircraft_logbook:read',   'name' => 'Read aircraft logbooks and entries',],
            ['slug' => 'aircraft_logbook:write',  'name' => 'Add/Edit aircraft logbooks and entries',],
        ]);

        // Add aircraft permissions to admin group
        Role::find(adminRole())->permissions()->attach([
            'aircraft_logbook:delete',
            'aircraft_logbook:read',
            'aircraft_logbook:write'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Detach aircraft logbook permissions
        Role::find(adminRole())->permissions()->detach([
            'aircraft_logbook:delete',
            'aircraft_logbook:read',
            'aircraft_logbook:write',
        ]);

        // Delete aircraft logbook permissions
        Permission::destroy([
            'aircraft_logbook:delete',
            'aircraft_logbook:read',
            'aircraft_logbook:write',
        ]);

        Schema::table('aircraft_logbooks', function (Blueprint $table) {
            $table->dropForeign('aircraft_logbooks_aircraft_registration_foreign');
        });

        Schema::dropIfExists('aircraft_logbooks');
    }
}
