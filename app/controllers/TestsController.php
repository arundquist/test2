<?php

class TestsController extends \BaseController {

	public function getUpdated()
	{
		$course=Course::where('crn',10560)
			->where('term_id',19)
			->first();
		dd($course);
	}
	
	public function getSeats($model,$term_id)
	{
		$mods=$model::mysort()->get();
		dd($mods);
		$mods->load('courses');
		$countlist=array();
		//dd($mods);
		foreach ($mods AS $mod)
		{
			$name=strtolower($model);
			$enrollment=$mod->courses()
						->where('term_id', $term_id)
						->select(DB::raw('SUM(credits/4 * enrollment) as total'))
						->pluck('total');
			if ($enrollment != '')
			{
				$countlist[$mod->id]=['what'=>$mod->$name,
					'count'=>$enrollment];
			};
		};
		echo "<table>";
		foreach ($countlist AS $count)
		{
			echo "<tr><td>{$count['what']}</td><td>{$count['count']}</td></tr>";
		};
		echo "</table>";
	}

}