<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PivotAreaCourseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('area_course', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('area_id')->unsigned()->index();
			$table->integer('course_id')->unsigned()->index();
			$table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
			$table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
		});
	}



	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('area_course');
	}

}
