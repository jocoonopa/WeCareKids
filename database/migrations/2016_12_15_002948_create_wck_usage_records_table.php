<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWckUsageRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wck_usage_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('organization_id')->unsigned()->index()->nullable();
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->integer('child_id')->unsigned()->index()->nullable();
            $table->integer('usage_id')->unsigned();
            $table->string('usage_type');

            $table->timestamps();

            $table
                ->foreign('organization_id')
                ->on('organizations')
                ->references('id')                
                ->onDelete('cascade')
            ;

            $table
                ->foreign('user_id')
                ->on('users')
                ->references('id')                
                ->onDelete('set null')
            ;

            $table
                ->foreign('child_id')
                ->on('childs')
                ->references('id')                
                ->onDelete('set null')
            ;
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('organization_id')->unsigned()->index()->nullable();

            $table
                ->foreign('organization_id')
                ->on('organizations')
                ->references('id')                
                ->onDelete('cascade')
            ;
        });

        Schema::table('childs', function (Blueprint $table) {
            $table->integer('organization_id')->unsigned()->index()->nullable();

            $table
                ->foreign('organization_id')
                ->on('organizations')
                ->references('id')                
                ->onDelete('cascade')
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
        Schema::dropIfExists('wck_usage_records');

        Schema::table('childs', function (Blueprint $table) {
            $table->dropForeign('childs_organizatio_id_foreign');
            $table->dropColumn('organization_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_organizatio_id_foreign');
            $table->dropColumn('organization_id');
        });
    }
}
