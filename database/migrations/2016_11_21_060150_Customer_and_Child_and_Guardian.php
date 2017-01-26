<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomerAndChildAndGuardian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('childs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned()->index();

            $table
                ->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade')
            ;
        });

        Schema::create('guardians', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned()->index();

            $table
                ->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade')
            ;
        });

        Schema::create('child_guardian', function (Blueprint $table){
            $table->integer('child_id')->unsigned()->index()->nullable();
            $table->integer('guardian_id')->unsigned()->index()->nullable();
            $table
                ->foreign('child_id')
                ->references('id')
                ->on('childs')
                ->onDelete('cascade')
            ;

            $table
                ->foreign('guardian_id')
                ->references('id')
                ->on('guardians')
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
        Schema::drop('child_guardian');
        Schema::drop('childs');
        Schema::drop('guardians');
    }
}
