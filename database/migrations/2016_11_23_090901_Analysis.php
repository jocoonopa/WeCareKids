<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * ### Als=Analysis
 * ### Cxt=Context
 * ### Rpt=Report
 * ### Ib=Inbound
 */
class Analysis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('als_rpt_ib_channels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('public_key')->unique();
            $table->datetime('open_at');
            $table->datetime('close_at');
            $table->boolean('is_open')->default(true);
            $table->integer('creater_id')->unsigned()->index();
            $table->timestamps();

            $table
                ->foreign('creater_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
            ;
        });

        Schema::create('als_rpt_ib_cxts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('channel_id')->unsigned()->index();
            $table->integer('status')->unsigned()->default(0)->comment('0: 尚未提交 1: 已提交');
            $table->string('private_key')->unique();
            $table->string('child_name');
            $table->integer('child_sex')->unsigned()->nullable()->comment('0: 男生, 1:女生');
            $table->datetime('child_birthday');
            $table->string('school_name');
            $table->integer('grade_num')->unsigned()->nullable();
            $table->string('filler_name');
            $table->string('relation');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('content');
            $table->timestamps();

            $table
                ->foreign('channel_id')
                ->references('id')
                ->on('als_rpt_ib_channels')
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
        Schema::drop('als_rpt_ib_cxts');
        Schema::drop('als_rpt_ib_channels');
    }
}
