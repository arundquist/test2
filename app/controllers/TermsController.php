<?php

class TermsController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$terms=Term::orderBy('ay','DESC')
			->orderBy('season','DESC')
			->get();
		return View::make('terms.index')
			->with("terms",$terms);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('terms.create');
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
		$term=Term::findOrFail($id);
		// here I have to figure out how to
		// get the departments for that term
		// I think I need to get the courses
		// and then get the departments
		// or something.
		$depts=Dept::with(array('courses'=>function($query) use ($id)
			{
				$query->where('term_id','=',$id);
			}))->orderBy('shortname','ASC')->get();
		Session::put('term_id', $id);
		return View::make('terms.show')
			->with("depts",$depts);
	}
	
	public function sendback($id)
	{
		$term=Term::findOrFail($id);
		Session::put('term_id', $id);
		return Redirect::intended("terms/$id");
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        return View::make('terms.edit');
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
