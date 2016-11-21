<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AmtDimension extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amt_dimensions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('adn_id')->unsigned()->index()->nullable();
            $table->integer('sort')->default(0);

            $table
                ->foreign('adn_id')
                ->references('id')
                ->on('amt_dimensions')
                ->onDelete('cascade')
            ;
        });

        Schema::table('amt_cells', function (Blueprint $table){
            $table->integer('level')->unsigned();
            $table->integer('adn_id')->unsigned()->index();

            $table
                ->foreign('adn_id')
                ->references('id')
                ->on('amt_dimensions')
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
        Schema::drop('amt_dimensions');
        Schema::table('amt_cells', function ($table) {
            $table->dropColumn('level');
        });
    }
}
