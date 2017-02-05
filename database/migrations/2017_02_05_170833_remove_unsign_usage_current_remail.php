<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnsignUsageCurrentRemail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wck_usage_records', function (Blueprint $table) {
            $table->integer('current_remain')->signed()->change();
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
            $table->integer('current_remain')->unsigned()->change();
        });
    }
}
