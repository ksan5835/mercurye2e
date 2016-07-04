<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBizservicestaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('biz_service_staff', function(Blueprint $table)
        {
            $table->increments('service_staff_id');
            $table->integer('service_id')->unsigned();
            $table->integer('staff_id');
            $table->integer('create_systemuser_id');
            $table->integer('update_systemuser_id');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('biz_service_staff');
    }
}
