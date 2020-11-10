<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateQualificationsTable
 */
class CreateQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qualifications', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('slug')->primary();
            $table->string('color', 7)->default('#6c757d');
            $table->string('qualification');
            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();
        });

        // Add users permissions
        Permission::insert([
            ['slug' => 'qualifications:delete', 'name' => 'Delete qualifications',],
            ['slug' => 'qualifications:read',   'name' => 'Read qualifications',],
            ['slug' => 'qualifications:write',  'name' => 'Add/Edit qualifications',],
        ]);

        // Add users permissions to admin group
        Role::find(adminRole())->permissions()->attach([
            'qualifications:delete',
            'qualifications:read',
            'qualifications:write'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Detach permissions
        Role::find(adminRole())->permissions()->detach([
            'qualifications:delete',
            'qualifications:read',
            'qualifications:write'
        ]);

        // Delete Permissions
        Permission::destroy(['qualifications:delete', 'qualifications:read', 'qualifications:write']);

        Schema::dropIfExists('qualifications');
    }
}
