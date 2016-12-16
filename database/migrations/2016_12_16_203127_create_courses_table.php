<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->text('desc');
            $table->integer('min_level')->default(1);
            $table->integer('max_level')->default(20);

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('courses_replicas', function (Blueprint $table) {
            $table->integer('course_id')->unsigned()->index();
            $table->integer('replica_id')->unsigned()->index();

            $table
                ->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade')
            ;

            $table
                ->foreign('replica_id')
                ->references('id')
                ->on('amt_replicas')
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
        Schema::dropIfExists('courses_replicas');
        Schema::dropIfExists('courses');
    }
}
