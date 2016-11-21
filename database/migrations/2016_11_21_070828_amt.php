<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Amt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amts', function (Blueprint $table){
            $table->increments('id');
            $table->integer('creater_id')->unsigned()->index();
            $table->timestamps();

            $table
                ->foreign('creater_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
            ;
        });

        Schema::create('amt_cols', function (Blueprint $table){
            $table->increments('id');
            $table->integer('amt_id')->unsigned()->index();
            
            $table
                ->foreign('amt_id')
                ->references('id')
                ->on('amts')
                ->onDelete('cascade')
            ;
        });

        Schema::create('amt_cells', function (Blueprint $table){
            $table->increments('id');
            $table->integer('col_id')->unsigned()->index();
            $table->boolean('is_intersect')->default(true);
            $table->integer('group_id')->unsigned()->index()->nullable();

            $table
                ->foreign('col_id')
                ->references('id')
                ->on('amt_cols')
                ->onDelete('cascade')
            ;

            $table
                ->foreign('group_id')
                ->references('id')
                ->on('amt_cells')
                ->onDelete('cascade')
            ;
        });

        Schema::create('amt_qtn_ptes', function (Blueprint $table){
            $table->increments('id');
            $table->text('content');
            $table->string('available_value');
            $table->integer('creater_id')->unsigned()->index();
            $table->timestamps();

            $table
                ->foreign('creater_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
            ;
        });

        Schema::create('amt_qtns', function (Blueprint $table){
            $table->increments('id');
            $table->integer('aqp_id')->unsigned()->index();
            $table->integer('cell_id')->unsigned()->index();
            $table->string('condition_value');

            $table
                ->foreign('aqp_id')
                ->references('id')
                ->on('amt_qtn_ptes')
                ->onDelete('cascade')
            ;

            $table
                ->foreign('cell_id')
                ->references('id')
                ->on('amt_cells')
                ->onDelete('cascade')
            ;
        });

        Schema::create('amt_cxts', function (Blueprint $table){
            $table->increments('id');
            $table->integer('amt_id')->unsigned()->index();
            $table->integer('handler_id')->unsigned()->index();
            $table->integer('child_id')->unsigned()->index();
            $table->integer('status')->unsigned()->default(0);

            $table
                ->foreign('amt_id')
                ->references('id')
                ->on('amts')
                ->onDelete('cascade')
            ;

            $table
                ->foreign('handler_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
            ;

            $table
                ->foreign('child_id')
                ->references('id')
                ->on('childs')
                ->onDelete('cascade')
            ;

            $table->timestamps();
        });

        Schema::create('amt_col_cxts', function (Blueprint $table){
            $table->increments('id');
            $table->integer('col_id')->unsigned()->index();
            $table->integer('cxt_id')->unsigned()->index();
            $table->integer('status')->unsigned()->default(0);

            $table
                ->foreign('col_id')
                ->references('id')
                ->on('amt_cols')
                ->onDelete('cascade')
            ;

            $table
                ->foreign('cxt_id')
                ->references('id')
                ->on('amt_cxts')
                ->onDelete('cascade')
            ;
        });

        Schema::create('amt_cell_cxts', function (Blueprint $table){
            $table->increments('id');
            $table->integer('cell_id')->unsigned()->index();
            $table->integer('col_cxt_id')->unsigned()->index();
            $table->integer('status')->unsigned()->default(0);
        });

        Schema::create('amt_qtn_cxts', function (Blueprint $table){
            $table->increments('id');

            $table->integer('qtn_id')->unsigned()->index();
            $table->integer('cell_cxt_id')->unsigned()->index();
            $table->integer('status')->unsigned()->default(0);
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('amt_qtn_cxts');
        Schema::drop('amt_cell_cxts');
        Schema::drop('amt_col_cxts');
        Schema::drop('amt_cxts');
        Schema::drop('amt_qtns');
        Schema::drop('amt_qtn_ptes');
        Schema::drop('amt_cells');
        Schema::drop('amt_cols');
        Schema::drop('amts');
    }
}
