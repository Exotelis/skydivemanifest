<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreatePermissionRoleTable
 */
class CreatePermissionRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->string('permission_slug');
            $table->unsignedBigInteger('role_id');
            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();

            $table->primary(['permission_slug','role_id']);

            $table->foreign('permission_slug')->references('slug')->on('permissions')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onDelete('cascade')->onUpdate('cascade');
        });

        // Add default permissions to default groups
        $admin = Role::find(adminRole());
        $user = Role::find(defaultRole());

        $admin->permissions()->attach(['permissions:read', 'personal', 'roles:delete', 'roles:read', 'roles:write']);
        $user->permissions()->attach(['personal']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permission_role', function (Blueprint $table) {
            $table->dropForeign('permission_role_permission_slug_foreign');
            $table->dropForeign('permission_role_role_id_foreign');
        });

        Schema::dropIfExists('permission_role');
    }
}
