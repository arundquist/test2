<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PivotCourseTimeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('course_time', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('course_id')->unsigned()->index();
			$table->integer('time_id')->unsigned()->index();
			$table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
			$table->foreign('time_id')->references('id')->on('times')->onDelete('cascade');
		});
	}



	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('course_time');
	}

}
