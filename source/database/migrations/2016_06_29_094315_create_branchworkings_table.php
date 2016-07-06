<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchworkingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('biz_branch_workinghours', function(Blueprint $table)
        {
            $table->increments('workinghours_id');
            $table->string('day');
            $table->string('start_time');
            $table->string('end_time');
			$table->string('working');
			$table->integer('branch_id');
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
        Schema::drop('biz_branch_workinghours');
    }
}
