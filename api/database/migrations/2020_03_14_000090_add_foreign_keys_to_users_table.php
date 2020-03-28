<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToUsersTable
 */
class AddForeignKeysToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('default_invoice')->references('id')->on('addresses')
                ->onDelete('set null')->onUpdate('cascade');
            $table->foreign('default_shipping')->references('id')->on('addresses')
                ->onDelete('set null')->onUpdate('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onDelete('no action')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_default_invoice_foreign');
            $table->dropForeign('users_default_shipping_foreign');
            $table->dropForeign('users_role_id_foreign');
        });
    }
}
