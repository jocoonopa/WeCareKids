<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmtCellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amt_cells', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned()->index()->comment();
            $table->integer('level')->unsigned()->nullable()->comment();
            $table->integer('prev_id')->unsigned()->index()->nullable()->comment();
            $table->integer('next_id')->unsigned()->index()->nullable()->comment();
            $table->integer('league_id')->unsigned()->index()->nullable()->comment();
            $table->integer('step')->default(0)->comment();
            $table->text('statement')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table
                ->foreign('group_id')
                ->on('amt_diag_groups')
                ->references('id')                
                ->onDelete('cascade')
            ;

            $table
                ->foreign('prev_id')
                ->on('amt_cells')
                ->references('id')                
                ->onDelete('cascade')
            ;

            $table
                ->foreign('next_id')
                ->on('amt_cells')
                ->references('id')                
                ->onDelete('cascade')
            ;

            $table
                ->foreign('league_id')
                ->on('amt_cells')
                ->references('id')                
                ->onDelete('cascade')
            ;
        });

        Schema::create('cells_standards', function (Blueprint $table) {
            $table->integer('cell_id')->unsigned()->index()->nullable()->comment();
            $table->integer('standard_id')->unsigned()->index()->nullable()->comment();

            $table
                ->foreign('cell_id')
                ->on('amt_cells')
                ->references('id')                
                ->onDelete('cascade')
            ;

            $table
                ->foreign('standard_id')
                ->on('amt_diag_standards')
                ->references('id')                
                ->onDelete('cascade')
            ;
        }); 

        Schema::table('amt_replica_diag_groups', function (Blueprint $table) {
            $table->integer('current_cell_id')->unsigned()->index()->nullable();
            $table->integer('result_cell_id')->unsigned()->index()->nullable();
            $table->boolean('dir')->nullable();

            $table
                ->foreign('current_cell_id')
                ->on('amt_cells')
                ->references('id')                
                ->onDelete('cascade')
            ;

            $table
                ->foreign('result_cell_id')
                ->on('amt_cells')
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
        Schema::dropIfExists('amt_cells');
        Schema::dropIfExists('cells_standards');
        Schema::table('amt_replica_diag_groups', function (Blueprint $table) {
            $table->dropForeign('amt_cells_current_cell_id_foreign');
            $table->dropColumn('current_cell_id');
            $table->dropForeign('amt_cells_report_result_cell_id_foreign');
            $table->dropColumn('result_cell_id');
        });
    }
}
