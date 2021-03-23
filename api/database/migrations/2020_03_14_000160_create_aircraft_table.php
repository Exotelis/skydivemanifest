<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateAircraftTable
 */
class CreateAircraftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aircraft', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('registration')->primary();
            $table->date('dom')->nullable()->comment('Manufacturing date');
            $table->string('model');
            $table->unsignedSmallInteger('seats');
            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();
            $table->timestamp('put_out_of_service_at', 0)->nullable()->comment('deleted_at');
        });

        // Add aircraft permissions
        Permission::insert([
            ['slug' => 'aircraft:delete', 'name' => 'Delete aircraft',],
            ['slug' => 'aircraft:read',   'name' => 'Read aircraft',],
            ['slug' => 'aircraft:write',  'name' => 'Add/Edit aircraft',],
        ]);

        // Add aircraft permissions to admin group
        Role::find(adminRole())->permissions()->attach(['aircraft:delete', 'aircraft:read', 'aircraft:write']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Detach aircraft
        Role::find(adminRole())->permissions()->detach(['aircraft:delete', 'aircraft:read', 'aircraft:write']);

        // Delete aircraft
        Permission::destroy(['aircraft:delete', 'aircraft:read', 'aircraft:write']);

        Schema::dropIfExists('aircraft');
    }
}
