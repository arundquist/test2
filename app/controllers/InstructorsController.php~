<?php

class InstructorsController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$instructors=Instructor::orderBy('name')->get();
		return View::make('instructors.index')
			->with("instructors", $instructors);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('instructors.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$instructor=Instructor::findOrFail($id);
		$cs=$instructor->courses()->where("term_id",'=',Session::get('term_id'))->get();
		$cs->load('instructors', 'hps','room.building','dept','times', 'areas','term');
		return View::make('courses.index')
			->with('courses',$cs);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        return View::make('instructors.edit');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
	
	public function history($id)
	{
		$instructor=Instructor::findOrFail($id);
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
	}

}
