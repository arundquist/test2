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
//URL::forceScheme('https');
// splash screen
Route::get('/', function()
	{
		return View::make('splash');
	});

// filters
Route::when('Courses/*', 'termwithredirect');
Route::when('depts/*', 'termwithredirect');
Route::when('hps/*', 'termwithredirect');
Route::when('areas/*', 'termwithredirect');
Route::when('instructors/*', 'termwithredirect');
Route::when('rooms/*', 'termwithredirect');
Route::when('buildings/*', 'termwithredirect');
Route::when('times/*', 'termwithredirect');
Route::when('tests/heatmap', 'termwithredirect');
Route::when('tests/lowenrolled/*', 'termwithredirect');
Route::when('tests/full','termwithredirect');


Route::get('crndetails/{crn}','DataController@crndetails');
Route::get('clearenrollments/{term_id}', 'DataController@clearenrollment');

Route::get('testallcrn/{crn}', function($crn)
	{
		$courses=Course::where('crn',$crn)->get();
		$courses->load('instructors', 'hps','room.building','dept','times', 'areas');
		return View::make('courses.index')
			->with('courses',$courses);
	});

Route::get('fysemdescriptions', function()
{
	$dept=Dept::where('shortname', 'FSEM')->first();
	$courses=Course::where('dept_id',$dept->id)->orderBy('id','DESC')->get();
	//dd($courses);
	return View::make('courses.fysem')
		->with('courses',$courses);
});


Route::get('instructorhistory/{id}', 'InstructorsController@history');
Route::get('deptcopy/{dept_id}', 'DataController@deptforcopying');
Route::get('deptallcopy/{dept_id}', 'DataController@deptAllforcopying');



Route::get('unsetterm', function()
	{
		Session::forget('term_id');
	});


Route::get('googlecalendar/{type}/{id}/{term_id}', 'TimesController@makecalendar');
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



Route::get('crns' , 'DataController@getcrns');
Route::get('getalldata' , 'DataController@getalldata');
Route::get('getsingle/{crn}/{year}/{term}', 'DataController@getsingle');
Route::get('crnsactual/{year}/{term}', 'DataController@getcrnsactual');
Route::get('fromlist', 'DataController@grabfromlist');
Route::get('testsave', 'CoursesController@saveone');
Route::get('testmulttimes', 'DataController@testmulttimes');
Route::get('commontime/{term}/{time}', 'TimesController@common');
Route::get('Courses/DepartmentCourses/{dept_id}', 'CoursesController@deptcourses');
Route::get('updatebyid/{id}', 'DataController@updatebyid');

Route::get('grabterm/{year}/{season}', 'DataController@grabterm');
Route::get('bookmarklets', function(){
	return View::make('tests.bookmarklets');
});

Route::get('testurl', function(){
	$url="https://piperline.hamline.edu/pls/prod/hamschedule.P_TermLevlPage?term_in=201715&levl_in=UG&key_in=&supress_others_in=N&format_in=L&sort_flag_in=S";
	$all=file_get_contents($url,FILE_SKIP_EMPTY_LINES);
	dd($all);

});

Route::get('phpinfo', function(){
	phpinfo();
});

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

Route::get('json', function()
{
	$courses=Course::where('term_id', '>', 19)->select('id','dept_id', 'number','section','enrolled')->get();
	return $courses;
});

Route::get('courseview/{model}/{id}',  array('as' => 'courseview', function($model,$id)
	{
		$mod=$model::findOrFail($id);
		$cs=$mod->courses()->where("term_id",'=',Session::get('term_id'))->get();
		$cs->load('instructors', 'hps','room.building','dept','times', 'areas','term');
		return View::make('courses.index')
			->with('courses',$cs);
	}));

//Route::get('lowenrolled', 'TestsController@lowenrolled');

Route::get('testcourseview/{model}/{id}', function($model,$id)
	{
		$cs=Helper::courselist($model,$id);
		return View::make('courses.index')
			->with('courses',$cs);
	});

Route::get('testhelper', function()
	{
		return Helper::helloWorld();
	});

Route::get('testdate', function()
	{
		$cs=Course::find(10);
		Return $cs->created_at->diffForHumans();
	});

// resources
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

Route::controller('tests', 'TestsController');

Route::controller('evals', 'EvalsController');

Route::controller('reports', 'ReportsController');
