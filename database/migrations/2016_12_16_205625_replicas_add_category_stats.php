<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReplicasAddCategoryStats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amt_replicas', function (Blueprint $table) {
            $table->text('step_1_stats')->nullable()->comment('step 1 分類統計等級');
            $table->text('step_2_stats')->nullable()->comment('九大分類統計等級');
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
            $table->dropColumn('step_1_stats');
            $table->dropColumn('step_2_stats');
        });
    }
}
