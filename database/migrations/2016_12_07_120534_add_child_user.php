<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChildUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('child_user', function (Blueprint $table) {
            $table->integer('child_id')->unsigned()->index()->nullable();
            $table->integer('user_id')->unsigned()->index()->nullable();

            $table
                ->foreign('child_id')
                ->references('id')
                ->on('childs')
                ->onDelete('cascade')
            ;

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::drop('child_user');
    }
}
