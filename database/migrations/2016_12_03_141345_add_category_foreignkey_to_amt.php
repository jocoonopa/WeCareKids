<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryForeignkeyToAmt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amt_diag_groups', function (Blueprint $table) {
            $table->integer('amt_id')->unsigned()->index()->nullable()->comment('指向amts');

            $table
                ->foreign('amt_id')
                ->references('id')
                ->on('amts')
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
        Schema::table('amt_diag_groups', function (Blueprint $table) {
            $table->dropForeign('amt_diag_groups_amt_id_foreign');
            $table->dropColumn('amt_id');
        });
    }
}
