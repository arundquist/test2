<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PivotCourseHpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('course_hp', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('course_id')->unsigned()->index();
			$table->integer('hp_id')->unsigned()->index();
			$table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
			$table->foreign('hp_id')->references('id')->on('hps')->onDelete('cascade');
		});
	}



	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('course_hp');
	}

}
