<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChildSchemaUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('childs', function (Blueprint $table) {
            $table->dropForeign('childs_customer_id_foreign');
            $table->dropColumn('customer_id');
            
            $table->integer('sex')->nullable()->comment('0: 男生, 1:女生');
            $table->string('name', 50);
            $table->string('school_name', 100);
            $table->dateTime('birthday')->nullable();
            $table->string('identifier');

            $table->timestamps();
        });

        Schema::table('guardians', function (Blueprint $table) {
            $table->dropForeign('guardians_customer_id_foreign');
            $table->dropColumn('customer_id');
            
            $table->integer('sex')->nullable()->comment('0: 男生, 1:女生');
            $table->string('name', 50);
            $table->dateTime('birthday')->nullable();
            $table->string('mobile', 50);
            $table->string('email', 100);

            $table->timestamps();
        });
        
        Schema::drop('customers');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
