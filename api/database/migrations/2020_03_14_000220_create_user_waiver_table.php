<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateUserWaiverTable
 */
class CreateUserWaiverTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_waiver', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('waiver_id');
            $table->mediumText('signature');
            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();

            $table->primary(['user_id', 'waiver_id']);

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('waiver_id')->references('id')->on('waivers')
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
        Schema::table('user_waiver', function (Blueprint $table) {
            $table->dropForeign('user_waiver_user_id_foreign');
            $table->dropForeign('user_waiver_waiver_id_foreign');
        });

        Schema::dropIfExists('user_waiver');
    }
}
