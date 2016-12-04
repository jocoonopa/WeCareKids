<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Diag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creater_id')->unsigned()->index()->comment('指向[users]');
            $table->timestamps();

            $table
                ->foreign('creater_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
            ;
        });

        Schema::create('amt_categorys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned()->index();
            $table->boolean('is_final')->comment('若為true表示為最底分類, 例如: 臉部肌肉, 追視');
            $table->string('content');
            $table->integer('step')->unsigned()->nullable();

            $table
                ->foreign('parent_id')
                ->references('id')
                ->on('amt_categorys')
                ->onDelete('cascade')
            ;
        });

        Schema::create('amt_diag_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned()->index();
            $table->string('content');

            $table
                ->foreign('category_id')
                ->references('id')
                ->on('amt_categorys')
                ->onDelete('cascade')
            ;
        });

        Schema::create('amt_diags', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned()->index();
            $table->string('description');
            $table->integer('type')->unsigned()->comment('{0: 是非, 1: 選項, 2: 範圍, 3: 單選}');
            $table->string('available_value')->comment('0, [option1, option2, option3], {m: number,M: number,i: number}');
            
            $table
                ->foreign('group_id')
                ->references('id')
                ->on('amt_diag_groups')
                ->onDelete('cascade')
            ;
        });

        Schema::create('amt_diag_standards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('diag_id')->unsigned()->index()->comment('指向[amt_diags]');
            $table->integer('min_level')->unsigned()->comment('根據年齡,答題決定');
            $table->integer('max_level')->unsigned()->comment('根據年齡,答題決定');
            $table->string('condition_value')->nullable()->comment('0, [option1, option2, option3], {m: number,M: number,i: number}');
            $table->boolean('is_empty')->comment('無挑件, 判斷時跳過');
            $table->integer('step')->unsigned()->comment('升降階的值');

            $table
                ->foreign('diag_id')
                ->references('id')
                ->on('amt_diags')
                ->onDelete('cascade')
            ;
        });

        Schema::create('amt_replicas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amt_id')->unsigned()->index()->comment('指向[amts]');
            $table->integer('child_id')->unsigned()->index()->comment('指向[childs]');
            $table->integer('creater_id')->unsigned()->index()->comment('指向[user]');
            $table->integer('status')->unsigned()->comment('0: 尚未開始, 1: 作答中, 2: 已完成');
            $table->integer('current_group_id')->unsigned()->index()->nullable()->commemt('指向[amt_replica_diag_groups]');
            $table->timestamps();
            $table->softDeletes();

            $table
                ->foreign('amt_id')
                ->references('id')
                ->on('amts')
                ->onDelete('cascade')
            ;

            $table
                ->foreign('child_id')
                ->references('id')
                ->on('childs')
                ->onDelete('cascade')
            ;

            $table
                ->foreign('creater_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
            ;
        });

        Schema::create('amt_replica_diag_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned()->index()->comment('指向[amt_diag_groups]');
            $table->integer('replica_id')->unsigned()->index()->comment('指向[amt_replicas]');
            $table->integer('current_diag_id')->unsigned()->index()->nullable()->comment('指向[amt_replica_diags]');
            $table->integer('level')->unsigned()->comment('根據年齡,答題決定');
            $table->integer('status')->unsigned()->comment('0: 尚未開始, 1: 作答中, 2: 已完成');

            $table
                ->foreign('group_id')
                ->references('id')
                ->on('amt_diag_groups')
                ->onDelete('cascade')
            ;

            $table
                ->foreign('replica_id')
                ->references('id')
                ->on('amt_replicas')
                ->onDelete('cascade')
            ;
        });

        Schema::create('amt_replica_diags', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('diag_id')->unsigned()->index();
            $table->integer('standard_id')->unsigned()->index()->nullable()->comment('指向[amt_diag_standards]');
            $table->integer('group_id')->unsigned()->index()->comment('指向[amt_replica_diag_groups]');
            $table->integer('level')->unsigned()->comment('根據年齡,答題決定');
            $table->string('value')->nullable()->comment('0, [option1, option2, option3], {m: number,M: number,i: number}');

            $table
                ->foreign('diag_id')
                ->references('id')
                ->on('amt_diags')
                ->onDelete('cascade')
            ;

            $table
                ->foreign('group_id')
                ->references('id')
                ->on('amt_replica_diag_groups')
                ->onDelete('cascade')
            ;

            $table
                ->foreign('standard_id')
                ->references('id')
                ->on('amt_diag_standards')
                ->onDelete('cascade')
            ;
        });

        Schema::create('amt_replica_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('replica_id')->unsigned()->index()->comment('指向[amt_replicas]');
            $table->text('logs');
            $table->timestamps();

            $table
                ->foreign('replica_id')
                ->references('id')
                ->on('amt_replicas')
                ->onDelete('cascade')
            ;
        });

        Schema::table('amt_replicas', function (Blueprint $table) {
            $table
                ->foreign('current_group_id')
                ->references('id')
                ->on('amt_replica_diag_groups')
                ->onDelete('cascade')
            ;
        });

        Schema::table('amt_replica_diag_groups', function (Blueprint $table) {
            $table
                ->foreign('current_diag_id')
                ->references('id')
                ->on('amt_replica_diags')
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
        Schema::drop('amt_replica_logs');
        Schema::drop('amt_replica_diags');
        Schema::drop('amt_replica_diag_groups');
        Schema::drop('amt_replicas');
        Schema::drop('amt_diag_standards');
        Schema::drop('amt_diags');
        Schema::drop('amt_diag_groups');
        Schema::drop('amt_categorys');
        Schema::drop('amts');
    }
}
