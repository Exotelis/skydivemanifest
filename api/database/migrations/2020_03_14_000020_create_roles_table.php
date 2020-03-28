<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRolesTable
 */
class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('deletable')->default(true);
            $table->boolean('editable')->default(true);
            $table->string('name')->unique();
            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();
        });

        // Add default roles
        Role::insert([
            ['id' => adminRole(), 'deletable' => false, 'editable' => false, 'name' => 'Administrator'],
            ['id' => defaultRole(), 'deletable' => false, 'editable' => false, 'name' => 'User'],
        ]);

        // Add permissions
        Permission::insert([
            ['slug' => 'roles:delete', 'name' => 'Delete roles',],
            ['slug' => 'roles:read',   'name' => 'Read roles',],
            ['slug' => 'roles:write',  'name' => 'Add/Edit roles',],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Delete permissions
        Permission::destroy(['roles:delete', 'roles:read', 'roles:write']);

        Schema::dropIfExists('roles');
    }
}
