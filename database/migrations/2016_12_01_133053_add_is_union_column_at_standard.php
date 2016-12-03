<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsUnionColumnAtStandard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amt_diag_standards', function (Blueprint $table) {
            $table->boolean('is_union')->default(false)->comment('是否為or條件');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amt_diag_standards', function ($table) {
            $table->dropColumn('is_union');
        });
    }
}
