<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchScheduleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('batch_schedule', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('batch_id')->unsigned();
			$table->integer('franchisee_id')->unsigned()->nullable();
			$table->date('batch_date');
			$table->time('start_time');
			$table->time('end_time');
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned()->nullable();
			$table->timestamps();
		});
		
		Schema::table('batch_schedule', function(Blueprint $table)
		{
			$table->foreign('created_by')->references('id')->on('users');
			$table->foreign('updated_by')->references('id')->on('users');
			$table->foreign('batch_id')->references('id')->on('batches');
			$table->foreign('franchisee_id')->references('id')->on('franchisees');
			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('batch_schedule');
	}

}
