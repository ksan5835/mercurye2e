<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('customer', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('user_title');
            $table->string('first_name');
            $table->string('last_name');
			$table->string('email');
			$table->string('password');
			$table->string('mobile');
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
        Schema::drop('customer');
    }
}
