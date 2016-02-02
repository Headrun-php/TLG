<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassesMasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('classes_master', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('course_master_id')->unsigned();
			$table->string('class_name', 150);
			$table->integer('class_start_age');
			$table->integer('class_end_age');
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned()->nullable();
			$table->timestamps();
		});
		
		Schema::table('classes_master', function(Blueprint $table)
		{
			$table->foreign('created_by')->references('id')->on('users');
			$table->foreign('updated_by')->references('id')->on('users');
			$table->foreign('course_master_id')->references('id')->on('courses_master');
			
		
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('classes_master');
	}

}
