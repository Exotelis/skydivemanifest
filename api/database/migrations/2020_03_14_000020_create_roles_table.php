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
            $table->id();
            $table->string('color', 7)->default('#6c757d');
            $table->boolean('deletable')->default(true)->comment('Determines if role is deletable');
            $table->boolean('editable')->default(true)->comment('Determines if permissions are editable');
            $table->string('name')->unique();
            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();
        });

        // Add default roles
        Role::insert([
            [
                'id' => adminRole(),
                'color' => '#1d2530',
                'deletable' => false,
                'editable' => false,
                'name' => 'Administrator'
            ],
            [
                'id' => defaultRole(),
                'color' => '#ed7c3b',
                'deletable' => false,
                'editable' => false,
                'name' => 'User'
            ],
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
