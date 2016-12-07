<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LinkAmtAls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amt_als_rpts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id')->unsigned()->index();
            $table->integer('cxt_id')->unsigned()->index()->nullable();
            $table->integer('replica_id')->unsigned()->index()->nullable();
            $table->timestamps();

            $table
                ->foreign('owner_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
            ;

            $table
                ->foreign('cxt_id')
                ->references('id')
                ->on('als_rpt_ib_cxts')
                ->onDelete('cascade')
            ;

            $table
                ->foreign('replica_id')
                ->references('id')
                ->on('amt_replicas')
                ->onDelete('cascade')
            ;
        });

        Schema::table('amt_replicas', function (Blueprint $table) {
            $table->integer('report_id')->unsigned()->index()->nullable()->comment('指向amt_als_rpt');

            $table
                ->foreign('report_id')
                ->references('id')
                ->on('amt_als_rpts')
                ->onDelete('cascade')
            ;
        });

        Schema::table('als_rpt_ib_cxts', function (Blueprint $table) {
            $table->integer('report_id')->unsigned()->index()->nullable()->comment('指向amt_als_rpt');

            $table
                ->foreign('report_id')
                ->references('id')
                ->on('amt_als_rpts')
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
        Schema::table('amt_replicas', function (Blueprint $table) {
            $table->dropForeign('amt_replicas_report_id_foreign');
            $table->dropColumn('report_id');
        });

        Schema::table('als_rpt_ib_cxts', function (Blueprint $table) {
            $table->dropForeign('als_rpt_ib_cxts_report_id_foreign');
            $table->dropColumn('report_id');
        });

        Schema::drop('amt_als_rpts');
    }
}
