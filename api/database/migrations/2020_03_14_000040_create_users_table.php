<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateUsersTable
 */
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedInteger('default_invoice')->nullable();
            $table->unsignedInteger('default_shipping')->nullable();
            $table->date('dob')->comment('date of birth');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedInteger('failed_logins')->default(0);
            $table->string('firstname')->index();
            $table->enum('gender', ['m', 'f', 'd', 'u'])->default('u');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_logged_in')->nullable();
            $table->string('lastname')->index();
            $table->string('locale')->default('en');
            $table->timestamp('lock_expires')->nullable();
            $table->string('middlename')->nullable();
            $table->string('mobile')->nullable();
            $table->string('password')->nullable();
            $table->boolean('password_change')->default(false)->comment('Force password change');
            $table->string('phone')->nullable();
            $table->unsignedInteger('role_id')->nullable();
            $table->string('username')->nullable()->unique();
            $table->string('timezone')->nullable();
            $table->boolean('tos')->default(false)->comment('Terms of Service accepted');
            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();
            $table->softDeletes();
        });

        // Add users permissions
        Permission::insert([
            ['slug' => 'users:delete', 'name' => 'Delete users',],
            ['slug' => 'users:read',   'name' => 'Read users',],
            ['slug' => 'users:write',  'name' => 'Add/Edit users',],
        ]);

        // Add users permissions to admin group
        Role::find(adminRole())->permissions()->attach(['users:delete', 'users:read', 'users:write']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Detach permissions
        Role::find(adminRole())->permissions()->detach(['users:delete', 'users:read', 'users:write']);

        // Delete Permissions
        Permission::destroy(['users:delete', 'users:read', 'users:write']);

        Schema::dropIfExists('users');
    }
}
