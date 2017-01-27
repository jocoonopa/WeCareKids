<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreaterInOrganization extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->integer('creater_id')->unsigned()->index()->nullable();

            $table
                ->foreign('creater_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
            ;

            $table->integer('owner_id')->unsigned()->index()->nullable();

            $table
                ->foreign('owner_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
            ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropForeign('organizations_creater_id_foreign');
            $table->dropColumn('creater_id');

            $table->dropForeign('organizations_owner_id_foreign');
            $table->dropColumn('owner_id');
        });
    }
}
