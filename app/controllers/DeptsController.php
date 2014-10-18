<?php

class DeptsController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$depts=Dept::orderBy('shortname', 'ASC')->get();
		return View::make('depts.index')
			->with('depts', $depts);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('depts.create');
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
		$mod=Dept::findOrFail($id);
		$cs=Helper::courselistwithmodel($mod);
		$title="Department: {$mod->shortname} ";
		$title.=link_to_action('TestsController@getPietest', 
				'enrollment breakdown',
				[$mod->id]);
		return View::make('courses.index')
			->with('courses',$cs)
			->with('title',$title);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        return View::make('depts.edit');
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
