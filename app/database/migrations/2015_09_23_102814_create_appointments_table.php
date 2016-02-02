<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('appointments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('franchisee_id')->unsigned()->nullable();
			$table->enum('appointment_type', array('BIRTHDAY', 'INTRO'));
			$table->date('appointment_date');
			$table->time('appointment_start_time');
			$table->time('appointment_end_time');
			$table->integer('customer_id')->unsigned()->nullable();
			$table->integer('student_id')->unsigned()->nullable();
			$table->integer('user_id')->unsigned()->nullable();
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned()->nullable();
			$table->timestamps();
		});
		
		
		Schema::table('appointments', function(Blueprint $table)
		{
			$table->foreign('created_by')->references('id')->on('users');
			$table->foreign('updated_by')->references('id')->on('users');
			$table->foreign('customer_id')->references('id')->on('customers');
			$table->foreign('student_id')->references('id')->on('students');
			$table->foreign('user_id')->references('id')->on('users');
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
		Schema::drop('appointments');
	}

}
