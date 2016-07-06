<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('branchs', function(Blueprint $table)
        {
            $table->increments('branch_id');
			$table->string('branch_name');
            $table->integer('branch_phone');
            $table->integer('branch_landline');
			$table->string('branch_email');
            $table->string('branch_fax');
            $table->string('branch_url');
            $table->string('branch_address1');
			$table->string('branch_address2');
            $table->integer('branch_city_id');
            $table->integer('branch_zipcode');
			$table->integer('biz_id');
            $table->integer('provider_id');
            $table->string('is_active');
            $table->integer('timezone_id');
			$table->integer('currency_id');
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
        Schema::drop('branchs');
    }
}
