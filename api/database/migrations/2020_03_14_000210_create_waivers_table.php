<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateWaiversTable
 */
class CreateWaiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waivers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('is_active')->default(false);
            $table->string('title');
            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();
        });

        // Add waivers permissions
        Permission::insert([
            ['slug' => 'waivers:delete', 'name' => 'Delete waivers',],
            ['slug' => 'waivers:read',   'name' => 'Read waivers',],
            ['slug' => 'waivers:write',  'name' => 'Add/Edit waivers',],
        ]);

        // Add waivers permissions to admin group
        Role::find(adminRole())->permissions()->attach(['waivers:delete', 'waivers:read', 'waivers:write']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Detach waivers
        Role::find(adminRole())->permissions()->detach(['waivers:delete', 'waivers:read', 'waivers:write']);

        // Delete waivers
        Permission::destroy(['waivers:delete', 'waivers:read', 'waivers:write']);

        Schema::dropIfExists('waivers');
    }
}
