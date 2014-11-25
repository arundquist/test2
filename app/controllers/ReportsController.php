<?php

class ReportsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /reports
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}
	
		
	public function totalsectionsfull($dept_id, $term_ids)
	{
		$cs=Course::with('hps','instructors')->where('dept_id',$dept_id)
			->whereIn('term_id', $term_ids)
			->get();
		$all['totalsections']=$cs->count();
		$all['W']=0;
		$all['O']=0;
		$all['GIL']=0;
		$all['DB']=0;
		$all['fysem']=0;
		$all['less20']=0;
		$all['more20']=0;
		$all['more40']=0;
		$all['headcount']=0;
		$all['credithours']=0;
		$all['faculty']=array();
		$faccredits=array();
		//$all['facultycredits']=array();
		foreach ($cs AS $c)
		{
			$hps=$c->hps->lists('letter');
			if (in_array('W', $hps) || in_array('T', $hps))
				$all['W']++;
			if (in_array('O', $hps))
				$all['O']++;
			if (count(array_intersect(['G', 'I', 'L'], $hps)) > 0)
				$all['GIL']++;
			if (count(array_intersect(['H','F','S','N'], $hps)) > 0)
				$all['DB']++;
			if ($c->enrollmentmax <= 20)
				$all['less20']++;
			if ($c->enrollmentmax <= 40 && $c->enrollmentmax > 20)
				$all['more20']++;
			if ($c->enrollmentmax > 40)
				$all['more40']++;
			$all['headcount']+=$c->enrollment;
			$all['credithours']+=($c->enrollment)*($c->credits);
			foreach ($c->instructors AS $instructor)
			{
				
				if (!(in_array($instructor->name, $all['faculty'])))
					$all['faculty'][$instructor->id]=$instructor->name;
				
			};
			
		};
		$temp=$all['faculty'];
		foreach ($temp AS $iid=>$name)
		{
			$who=Instructor::findOrFail($iid);
			$whocs=$who->courses()->whereIn('term_id', $term_ids)->get();
			//dd($whocs->lists('title'));
			$deptcnt=0;
			$notdeptcnt=0;
			$deptsecs=0;
			$notdepsecs=0;
			foreach ($whocs AS $c)
			{
				if ($c->dept_id==$dept_id)
				{
					$deptcnt+=($c->credits)/4;
					$deptsecs++;
				}
				else
				{
					$notdeptcnt+=($c->credits)/4;
					$notdepsecs++;
				};
			};
			$all['faculty'][$iid]="$name [$deptsecs($deptcnt)-$notdepsecs($notdeptcnt)]";
		};
		$fsemid=Dept::where('shortname', 'FSEM')->first()->id;
		foreach ($all['faculty'] AS $iid=>$name)
		{
			$inst=Instructor::findOrFail($iid);
			//dd($inst->courses);
			$all['fysem']+=$inst->courses()->whereIn('term_id', $term_ids)
				->where('dept_id', $fsemid)->count();
		};
		
		/*
		($all['faculty'] AS $iid => $name)
			$all['faculty'][$iid]=$name." (".$faccredits[$iid].")";
		*/
		//$all['facultycredits']=$faccredits;
		$all['faculty']=implode(', ', $all['faculty']);
			
		//dd($all);
		return($all);
	}
	
	public function terms($ay)
	{
		$ay=substr($ay, 0, 2);
		$ay='20'.$ay;
		$seasons=[11,12,13];
		$termids=Term::where('ay',$ay)
			->whereIn('season', $seasons)
			->lists('id');
		return($termids);
	}
	

	public function getFachire()
	{
		$depts=Dept::lists('shortname','id');
		//dd($depts);
		$ays=["13-14","12-13","11-12"];
		return View::make('reports.fachire', 
			['depts'=> $depts,
			'ays'=>$ays]);
	}
	
	public function postFachire()
	{
		
		//$dept=Dept::where('shortname',$dept_name)->first();
		//$all=$this->totalsections($dept->id,2);
		//$aytermids=$this->terms('11-12');
		$deptid=Input::get('dept');
		$dept=Dept::findOrFail($deptid);
		$ays=Input::get('ays');
		foreach ($ays AS $ay)
		{
			$whole[$ay]=$this->totalsectionsfull($deptid,$this->terms($ay));
			
		};
		ksort($whole);
		$keys=array_keys($whole[$ays[0]]);
		return View::make('reports.fachirereport',
			['whole'=>$whole,
			'keys'=>$keys,
			'dept'=>$dept]);
	}

}