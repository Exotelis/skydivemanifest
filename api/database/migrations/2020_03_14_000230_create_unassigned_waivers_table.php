<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateUnassignedWaiversTable
 */
class CreateUnassignedWaiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unassigned_waivers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('email');
            $table->string('firstname');
            $table->ipAddress('ip');
            $table->string('lastname');
            $table->mediumText('signature');
            $table->unsignedInteger('waiver_id');
            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();

            $table->foreign('waiver_id')->references('id')->on('waivers')
                ->onDelete('cascade')->onUpdate('cascade');
        });

        // Add unassigned waivers permissions
        Permission::insert([
            ['slug' => 'unassigned_waivers:delete', 'name' => 'Delete unassigned waivers',],
            ['slug' => 'unassigned_waivers:read',   'name' => 'Read unassigned waivers',],
            ['slug' => 'unassigned_waivers:write',  'name' => 'Assign unassigned waivers',],
        ]);

        // Add unassigned waivers permissions to admin group
        Role::find(adminRole())
            ->permissions()
            ->attach(['unassigned_waivers:delete', 'unassigned_waivers:read', 'unassigned_waivers:write']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Detach unassigned waivers
        Role::find(adminRole())
            ->permissions()
            ->detach(['unassigned_waivers:delete', 'unassigned_waivers:read', 'unassigned_waivers:write']);

        // Delete unassigned waivers
        Permission::destroy(['unassigned_waivers:delete', 'unassigned_waivers:read', 'unassigned_waivers:write']);

        Schema::table('unassigned_waivers', function (Blueprint $table) {
            $table->dropForeign('unassigned_waivers_waiver_id_foreign');
        });

        Schema::dropIfExists('unassigned_waivers');
    }
}
