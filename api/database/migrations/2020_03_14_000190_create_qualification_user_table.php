<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateQualificationUserTable
 */
class CreateQualificationUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qualification_user', function (Blueprint $table) {
            $table->string('qualification_slug');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();

            $table->primary(['qualification_slug','user_id']);

            $table->foreign('qualification_slug')->references('slug')->on('qualifications')
                ->onDelete('cascade')->onUpdate('cascade');
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
        Schema::table('qualification_user', function (Blueprint $table) {
            $table->dropForeign('qualification_user_qualification_slug_foreign');
            $table->dropForeign('qualification_user_user_id_foreign');
        });

        Schema::dropIfExists('qualification_user');
    }
}
