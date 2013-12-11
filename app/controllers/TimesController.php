<?php

class TimesController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return View::make('times.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('times.create');
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
		$time=Time::findOrFail($id);
		$cs=$time->courses()->where("term_id",'=',Session::get('term_id'))->get();
		$cs->load('instructors', 'hps','room.building','dept','times', 'areas');
		return View::make('courses.index')
			->with('courses',$cs);
	}
	
	// ok, this works
	public function common($term_id, $time_id)
	{
		$time=Time::findOrFail($time_id);
		$courses=$time->courses()->where('term_id', '=', $term_id)->get();
		return View::make('times.show')->with("time", $time)
		->with("courses",$courses);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        return View::make('times.edit');
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

}