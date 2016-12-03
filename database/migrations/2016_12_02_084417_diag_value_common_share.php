<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DiagValueCommonShare extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amt_diag_pools', function (Blueprint $table) {
            $table->increments('id');
        });

        Schema::table('amt_diags', function (Blueprint $table) {
            $table->integer('pool_id')->unsigned()->index()->nullable();

            $table
                ->foreign('pool_id')
                ->references('id')
                ->on('amt_diag_pools')
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
        Schema::table('amt_diags', function ($table) {
            $table->dropColumn('pool_id');
        });

        Schema::drop('amt_diag_pools');
    }
}
