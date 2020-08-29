<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRegionsTable
 */
class CreateRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('region')->index();
            $table->unsignedSmallInteger('country_id');
            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();

            $table->foreign('country_id')->references('id')->on('countries')
                ->onDelete('cascade')->onUpdate('cascade');
        });

        // Add region permissions
        Permission::insert([
            ['slug' => 'regions:delete', 'name' => 'Delete regions',   'is_default' => false,],
            ['slug' => 'regions:read',   'name' => 'Read regions',     'is_default' => true,],
            ['slug' => 'regions:write',  'name' => 'Add/Edit regions', 'is_default' => false,],
        ]);

        // Add region permissions to admin group
        Role::find(adminRole())->permissions()->attach(['regions:delete', 'regions:read', 'regions:write']);
        // Add region permissions to user group
        Role::find(defaultRole())->permissions()->attach(['regions:read']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Detach permissions
        Role::find(adminRole())->permissions()->detach(['regions:delete', 'regions:read', 'regions:write']);
        Role::find(defaultRole())->permissions()->detach(['regions:read']);

        // Delete Permissions
        Permission::destroy(['regions:delete', 'regions:read', 'regions:write']);

        Schema::table('regions', function (Blueprint $table) {
            $table->dropForeign('regions_country_id_foreign');
        });

        Schema::dropIfExists('regions');
    }
}
