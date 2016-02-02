<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('classes', function(Blueprint $table)
		{
			$table->increments('id');	
			$table->string('class_name');
			$table->integer('course_id')->unsigned()->nullable();
			$table->integer('franchisee_id')->unsigned()->nullable();
			$table->integer('class_master_id')->unsigned()->nullable();
			$table->integer('created_by')->unsigned()->nullable();
			$table->integer('updated_by')->unsigned()->nullable();
			$table->timestamps();
		});
		
		Schema::table('classes', function(Blueprint $table)
		{
			$table->foreign('created_by')->references('id')->on('users');
			$table->foreign('updated_by')->references('id')->on('users');
			$table->foreign('course_id')->references('id')->on('courses');
			$table->foreign('class_master_id')->references('id')->on('classes_master');
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
		Schema::drop('classes');
	}

}
