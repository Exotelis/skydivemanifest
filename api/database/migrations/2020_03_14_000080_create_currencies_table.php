<?php

use App\Models\Currency;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCurrenciesTable
 */
class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('code')->primary();
            $table->string('currency');
            $table->string('symbol')->nullable();
            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();
        });

        // Add currency permissions
        Permission::insert([
            ['slug' => 'currencies:delete', 'name' => 'Delete currencies',],
            ['slug' => 'currencies:read',   'name' => 'Read currencies',],
            ['slug' => 'currencies:write',  'name' => 'Add/Edit currencies',],
        ]);

        // Add currency permissions to admin group
        Role::find(adminRole())->permissions()->attach(['currencies:delete', 'currencies:read', 'currencies:write']);
        // Add currency permissions to user group
        Role::find(defaultRole())->permissions()->attach(['currencies:read']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Detach permissions
        Role::find(adminRole())->permissions()->detach(['currencies:delete', 'currencies:read', 'currencies:write']);
        Role::find(defaultRole())->permissions()->detach(['currencies:read']);

        // Delete Permissions
        Permission::destroy(['currencies:delete', 'currencies:read', 'currencies:write']);

        Schema::dropIfExists('currencies');
    }
}
