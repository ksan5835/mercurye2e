<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerproviderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('customer_provider', function(Blueprint $table)
        {
            $table->increments('provider_user_id');
            $table->integer('customer_user_id');
            $table->string('request_status_id');
            $table->date('requested_date');
			$table->date('accepted_date');
			$table->string('status');
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
        Schema::drop('customer_provider');
    }
}
