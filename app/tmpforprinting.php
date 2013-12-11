<?php
Route::get('instructorhistory/{name}', function($name)
	{
		$instructor=Instructor::where('name','LIKE',"$name%")->first();
		$courses=$instructor->courses;
		$courses->load('instructors', 'hps','room.building','dept','times', 'areas', 'term');
		$roles = $courses->sortBy(function($course)
			{
				$sarray=["fall"=>".4",
				"winter"=>".1",
				"spring"=>".2",
				"summer"=>".3"];
				$ay=$course->term->ay;
				$season=$course->term->season;
				$num=$course->number;
				$num=$num/100000.0;
				$ay=3000-$ay-$sarray[$season]+$num;
				return $ay;
			});
		return View::make('courses.index')
			->with('courses',$courses);  
	});
