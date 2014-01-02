<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
	{
		return View::make('splash');
	});

Route::when('Courses/*', 'termwithredirect');
Route::when('depts/*', 'termwithredirect');
Route::when('hps/*', 'termwithredirect');
Route::when('areas/*', 'termwithredirect');
Route::when('instructors/*', 'termwithredirect');
Route::when('rooms/*', 'termwithredirect');
Route::when('buildings/*', 'termwithredirect');
Route::when('times/*', 'termwithredirect');


Route::get('/hithere/{name?}', function($name=NULL)
	{
		if ($name==NULL) {
			return "what's wrong with you?";
		};
		return "hi there! $name";
	}
	);

Route::get('crndetails/{crn}','DataController@crndetails');
Route::get('clearenrollments/{term_id}', 'DataController@clearenrollment');

Route::get('originalschedule', function()
	{
		return View::make('originalform');
	});


Route::get('spring2012/{which?}', function($which=NULL)
	{
		if ($which==NULL) {
			$url="http://localhost/spring2012.html";
		} else {
			$url="https://piperline.hamline.edu/pls/prod/hamschedule.P_TermLevlPage?term_in=201113&levl_in=UG&key_in=&supress_others_in=N&format_in=L&sort_flag_in=S";
		};
		$all=file_get_contents($url,FILE_SKIP_EMPTY_LINES);
		$f=preg_match_all("/hamschedule.*crn_in=([0-9]{5})/", $all, $matches);
		return var_dump($matches[1]);
	});

Route::get('testurl', function()
	{
		$course=Course::find(1685);
		return $course->url;
	});

Route::get('testspeed', function()
	{
		$url="https://piperline.hamline.edu/pls/prod/hamschedule.P_OneSingleCourse?term_in=200813&levl_in=UG&format_in=T&key_in=&sort_flag_in=S&supress_others_in=N&crn_in=36892";
		$timeinit=time();
		for($i=0; $i<100; $i++)
		{
			$all=file_get_contents($url, FILE_SKIP_EMPTY_LINES);
		};
		$timeend=time();
		$diff=$timeend-$timeinit;
		echo $diff;
	});

Route::get('checkcourses', function()
	{
		$cs=Course::has('areas', '=', '0')->get();
		return View::make('courses.index')
			->with('courses',$cs);
	});

Route::get('countnotenrolled', function()
	{
		$cnt=Course::where('enrollmentchecked','0')->count();
		return $cnt;
	});

Route::get('testallcrn/{crn}', function($crn)
	{
		$courses=Course::where('crn',$crn)->get();
		$courses->load('instructors', 'hps','room.building','dept','times', 'areas');
		return View::make('courses.index')
			->with('courses',$courses);
	});

Route::get('testallcourse/{dept}/{num}', function($dept,$num)
	{
		$department=Dept::where('shortname',$dept)->first();
		$courses=Course::join('terms', 'courses.term_id', '=', 'terms.id')
			->select('courses.*','terms.ay', 'terms.season')
			->where('dept_id',$department->id)
			->where('number',$num)
			->where('title', 'NOT LIKE', "LAB%")
			->orderBy('ay', 'DESC')
			->orderBy('season', 'DESC')
			->get();
		$courses->load('instructors', 'hps','room.building','dept','times', 'areas');
		return View::make('courses.index')
			->with('courses',$courses);
	});

Route::get('instructorhistory/{id}', 'InstructorsController@history');



Route::get('unsetterm', function()
	{
		Session::forget('term_id');
	});
Route::get('timetest', function()
	{
		$dt=Carbon\Carbon::createFromFormat('j-M-Y h:ia', '16-Feb-2009 1:01pm');
		$dt->next(\Carbon\Carbon::WEDNESDAY);
		$dt2 = Carbon\Carbon::create(2012, 1, 31, 11, 5, 0);
		echo $dt2->next(Carbon\Carbon::WEDNESDAY); 
		Return $dt;
	});
Route::get('googleclasshelp',function()
	{
		$term=Term::find(1);
		return $term->startdate;
	});
Route::get('googlecalendar/{type}/{id}/{term_id}', 'TimesController@makecalendar');
Route::get('testspeedfull', 'DataController@checkspeed');
Route::get('settermandredirect/{id}', 'TermsController@sendback');
Route::get('testgoogleplot/{dept}/{num}', array('as' => 'enrollment', function($dept,$num)
	{
		$department=Dept::where('shortname',$dept)->first();
		$courses=Course::join('terms', 'courses.term_id', '=', 'terms.id')
			->select('courses.*','terms.ay', 'terms.season')
			->where('dept_id',$department->id)
			->where('number',$num)
			->where('title', 'NOT LIKE', "LAB%")
			->orderBy('ay', 'ASC')
			->orderBy('season', 'ASC')
			->get();
		$courses->load('term');
		return View::make('history.enrollment')
			->with('courses', $courses)
			->with('dept', $department);
	}));

Route::get('grabenrollments', 'DataController@grabenrollments');

Route::get('testbootstrap', function()
	{
		return View::make('testing');
	});

Route::get('areasgrab/{id}', 'DataController@getsinglefromid');

Route::get('crns' , 'DataController@getcrns');
Route::get('getalldata' , 'DataController@getalldata');
Route::get('getsingle/{crn}/{year}/{term}', 'DataController@getsingle');
Route::get('crnsactual/{year}/{term}', 'DataController@getcrnsactual');
Route::get('fromlist', 'DataController@grabfromlist');
Route::get('testsave', 'CoursesController@saveone');
Route::get('commontime/{term}/{time}', 'TimesController@common');
Route::get('Courses/DepartmentCourses/{dept_id}', 'CoursesController@deptcourses');

Route::get('grabterm/{year}/{season}', 'DataController@grabterm');

Route::get('fixwrongterm', function()
	{
		$courses=Course::where('term_id','5')->get();
		foreach ($courses AS $course)
		{
			$course->times()->detach();
			$course->hps()->detach();
			$course->areas()->detach();
			$course->delete();
		};
	});

Route::get('test1', function()
	{
		$data=["ay"=>"2012",
			"season"=>"11",
			"dept"=>"CHEM",
			"building"=>"RSC",
			"times"=>[["beginning"=>"11:30am",
				"end"=>"12:30pm",
				"day"=>"Monday"]],
			"hps"=>["L"],
			"roomnumber"=>"130",
			"instructors"=>["Andy Rundquist"],
			"crn"=>"12346",
			"number"=>"1240",
			"section"=>"A"];
		Return Redirect::action('CoursesController@saveone',$data);
		/* echo "<pre>";
		var_dump($data);
		echo "</pre>"; */
	}
	);

Route::post('originalschedule', 'DataController@original');

Route::resource('tweets', 'TweetsController');

Route::resource('courses', 'CoursesController');

Route::resource('terms', 'TermsController');

Route::resource('depts', 'DeptsController');

Route::resource('instructors', 'InstructorsController');

Route::resource('times', 'TimesController');

Route::resource('rooms', 'RoomsController');

Route::resource('hps', 'HpsController');

Route::resource('buildings', 'BuildingsController');

Route::resource('areas', 'AreasController');