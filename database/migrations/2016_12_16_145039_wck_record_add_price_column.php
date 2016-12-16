<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WckRecordAddPriceColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wck_usage_records', function (Blueprint $table) {
            $table->integer('current_remain')->unsigned()->comment('此交易過後的剩餘總額');
            $table->integer('variety')->comment('此交易金額變化量');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wck_usage_records', function (Blueprint $table) {
            $table->dropColumn($current_remain);
            $table->dropColumn($variety);
        });
    }
}
