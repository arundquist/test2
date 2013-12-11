<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PivotDeptInstructorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dept_instructor', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('dept_id')->unsigned()->index();
			$table->integer('instructor_id')->unsigned()->index();
			$table->foreign('dept_id')->references('id')->on('depts')->onDelete('cascade');
			$table->foreign('instructor_id')->references('id')->on('instructors')->onDelete('cascade');
		});
	}



	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dept_instructor');
	}

}
