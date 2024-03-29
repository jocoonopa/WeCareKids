<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFillerSexInCxt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('als_rpt_ib_cxts', function (Blueprint $table) {
            $table->integer('filler_sex')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('als_rpt_ib_cxts', function (Blueprint $table) {
            $table->dropColumn('filler_sex');
        });
    }
}
