<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCountriesTable
 */
class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->smallIncrements('id');
            $table->string('country')->unique();
            $table->string('code')->unique();
            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();
        });

        // Add country permissions
        Permission::insert([
            ['slug' => 'countries:delete', 'name' => 'Delete countries',],
            ['slug' => 'countries:read',   'name' => 'Read countries',],
            ['slug' => 'countries:write',  'name' => 'Add/Edit countries',],
        ]);

        // Add country permissions to admin group
        Role::find(adminRole())->permissions()->attach(['countries:delete', 'countries:read', 'countries:write']);
        // Add country permissions to user group
        Role::find(defaultRole())->permissions()->attach(['countries:read']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Detach permissions
        Role::find(adminRole())->permissions()->detach(['countries:delete', 'countries:read', 'countries:write']);
        Role::find(defaultRole())->permissions()->detach(['countries:read']);

        // Delete Permissions
        Permission::destroy(['countries:delete', 'countries:read', 'countries:write']);

        Schema::dropIfExists('countries');
    }
}
