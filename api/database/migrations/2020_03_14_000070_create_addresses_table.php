<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateAddressesTable
 */
class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('city');
            $table->string('company')->nullable();
            $table->unsignedSmallInteger('country_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('middlename')->nullable();
            $table->string('postal');
            $table->unsignedInteger('region_id');
            $table->string('street');
            $table->unsignedInteger('user_id')->index();
            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();

            $table->foreign('region_id')->references('id')->on('regions')
                ->onDelete('no action')->onUpdate('no action');
            $table->foreign('country_id')->references('id')->on('countries')
                ->onDelete('no action')->onUpdate('no action');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
        });

        // Add address permissions
        Permission::insert([
            ['slug' => 'addresses:delete', 'name' => 'Delete addresses',],
            ['slug' => 'addresses:read',   'name' => 'Read addresses',],
            ['slug' => 'addresses:write',  'name' => 'Add/Edit addresses',],
        ]);

        // Add address permissions to admin group
        Role::find(adminRole())->permissions()->attach(['addresses:delete', 'addresses:read', 'addresses:write']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Detach permissions
        Role::find(adminRole())->permissions()->detach(['addresses:delete', 'addresses:read', 'addresses:write']);

        // Delete Permissions
        Permission::destroy(['addresses:delete', 'addresses:read', 'addresses:write']);

        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign('addresses_region_id_foreign');
            $table->dropForeign('addresses_country_id_foreign');
            $table->dropForeign('addresses_user_id_foreign');
        });

        Schema::dropIfExists('addresses');
    }
}
