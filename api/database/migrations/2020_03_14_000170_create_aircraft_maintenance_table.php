<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateAircraftMaintenanceTable
 */
class CreateAircraftMaintenanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aircraft_maintenance', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('aircraft_registration');
            $table->date('dom')->nullable()->comment('Maintenance date');
            $table->unsignedInteger('maintenance_at');
            $table->string('name')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('notified')->default(false);
            $table->unsignedInteger('notify_at')->nullable();
            $table->unsignedInteger('repetition_interval')
                ->nullable()
                ->comment('Restart period automatically after maintenance');
            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();

            $table->foreign('aircraft_registration')->references('registration')->on('aircraft')
                ->onDelete('no action')->onUpdate('cascade');
        });

        // Add aircraft maintenance permissions
        Permission::insert([
            ['slug' => 'aircraft_maintenance:delete', 'name' => 'Delete aircraft maintenance',],
            ['slug' => 'aircraft_maintenance:read',   'name' => 'Read aircraft maintenance'],
            ['slug' => 'aircraft_maintenance:write',  'name' => 'Add/Edit aircraft maintenance',],
        ]);

        // Add aircraft permissions to admin group
        Role::find(adminRole())->permissions()->attach([
            'aircraft_maintenance:delete',
            'aircraft_maintenance:read',
            'aircraft_maintenance:write'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Detach aircraft maintenance
        Role::find(adminRole())->permissions()->detach([
            'aircraft_maintenance:delete',
            'aircraft_maintenance:read',
            'aircraft_maintenance:write'
        ]);

        // Delete aircraft maintenance
        Permission::destroy(['aircraft_maintenance:delete', 'aircraft_maintenance:read', 'aircraft_maintenance:write']);

        Schema::table('aircraft_maintenance', function (Blueprint $table) {
            $table->dropForeign('aircraft_maintenance_aircraft_registration_foreign');
        });

        Schema::dropIfExists('aircraft_maintenance');
    }
}
