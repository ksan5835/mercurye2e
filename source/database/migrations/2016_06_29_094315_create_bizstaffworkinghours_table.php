<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBizstaffworkinghoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('biz_staff_workinghours', function(Blueprint $table)
        {
            $table->increments('workinghours_id');
            $table->string('day');
            $table->integer('staff_id');
			$table->integer('start_time');
			$table->integer('end_time');
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
        Schema::drop('biz_staff_workinghours');
    }
}
