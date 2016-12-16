<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategorySuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_suggestions', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_better')->default(true);
            $table->integer('min_level');
            $table->integer('max_level');
            $table->integer('category_id')->unsigned()->index()->nullable();
            $table->text('desc');

            $table
                ->foreign('category_id')
                ->references('id')
                ->on('amt_categorys')
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
        Schema::dropIfExists('category_suggestions');
    }
}
