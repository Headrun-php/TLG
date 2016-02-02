<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFranchiseesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('franchisees', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('franchisee_name');
			$table->string('franchisee_address');
			$table->string('franchisee_phone');
			$table->string('franchisee_official_email');
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned()->nullable();
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
		Schema::drop('franchisee');
	}

}
