<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('account', 100);
            $table->integer('points');
            $table->integer('contacter_id')->unsigned()->index()->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table
                ->foreign('contacter_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
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
        Schema::dropIfExists('organizations');
    }
}
