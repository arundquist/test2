<?php

class TimesController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$times=Time::orderBy('day', 'ASC')->get();
		return View::make('times.index')
			->with('times', $times);
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
		$mod=Time::findOrFail($id);
		$cs=Helper::courselistwithmodel($mod);
		$title="Day and time: {$mod->day} {$mod->beginning}-{$mod->end}";
		return View::make('courses.index')
			->with('courses',$cs)
			->with('title',$title);
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
	
	public function makecalendar($type, $id, $term_id)
	{
		$term=Term::findOrFail($term_id);
		$title="none";
		switch ($type)
		{
		case "tests":
			$title="{$term->ay} {$term->season} ";
			$title.=Session::getId();
			break;
		case "depts":
			$s=Dept::findOrFail($id);
			$title="{$s->shortname} {$term->ay} {$term->season}";
			break;
		case "hps":
			$s=Hp::findOrFail($id);
			$title="HP {$s->letter} {$term->ay} {$term->season}";
			break;
		case "times":
			$s=Time::findOrFail($id);
			$title="{$t->beginning} {$term->ay} {$term->season}";
			break;
		case "rooms":
			$s=Room::findOrFail($id);
			$title="{$s->building->name} {$s->number} {$term->ay} {$term->season}";
			break;
		case "areas":
			$s=Area::findOrFail($id);
			$title="Area {$s->area} {$term->ay} {$term->season}";
			break;
		case "instructors":
			$s=Instructor::findOrFail($id);
			$title="{$s->name} {$term->ay} {$term->season}";
			break;
		default: return "oops";
		};
		
		if($type=='tests')
			$cs=Course::whereIn('id', array_unique(Session::get('user.classes')))->get();
		else
			$cs=$s->courses()->where("term_id",'=',$term_id)->get();
		$cs->load('instructors', 'hps','rooms.building','dept','times', 'areas','term');
		$v=View::make('times.googlecalendar')
			->with('courses',$cs)
			->with('term', $term)
			->with('title', $title);
		return Response::make($v,"200")
			->header('Content-Type', 'text/calendar')
			->header('Content-Disposition', 'attachment; filename="test.ics"');
	}

}
