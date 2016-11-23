<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RelateDimensionAndCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amt_cols', function (Blueprint $table) {
            $table->integer('adn_id')->unsigned()->index()->nullable();

            $table
                ->foreign('adn_id')
                ->references('id')
                ->on('amt_dimensions')
                ->onDelete('cascade')
            ;
        });

        Schema::table('amt_qtn_cxts', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amt_cols', function ($table) {
            $table->dropColumn('adn_id');
        });
    }
}
