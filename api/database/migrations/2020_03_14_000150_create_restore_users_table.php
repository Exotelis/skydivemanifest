<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRestoreUsersTable
 */
class CreateRestoreUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restore_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('token');
            $table->unsignedInteger('user_id')->index()->nullable();;
            $table->timestamp('created_at', 0)->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restore_users', function (Blueprint $table) {
            $table->dropForeign('restore_users_user_id_foreign');
        });

        Schema::dropIfExists('restore_users');
    }
}
