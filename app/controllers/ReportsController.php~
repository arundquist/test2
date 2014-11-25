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
	
	public function totalsections($dept_id, $term_id)
	{
		$cs=Course::with('hps')->where('dept_id',$dept_id)
			->where('term_id', $term_id)
			->get();
		$all['totalsections']=$cs->count();
		$all['W']=0;
		$all['O']=0;
		$all['GIL']=0;
		$all['DB']=0;
		$all['less20']=0;
		$all['more20']=0;
		$all['more40']=0;
		$all['headcount']=0;
		$all['credithours']=0;
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
			
		};
		
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
	
	public function fullyeardetail($deptid,$terms)
	{
		foreach ($terms AS $termid)
		{
			$full[]=$this->totalsections($deptid, $termid);
		};
		
		$keys=array_keys($full[0]);
		
		foreach ($keys AS $key)
		{
			$fulltotals[$key]=array_sum(array_column($full,$key));
		};
		return($fulltotals);
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
			$whole[$ay]=$this->fullyeardetail($deptid,$this->terms($ay));
			
		};
		ksort($whole);
		$keys=array_keys($whole[$ays[0]]);
		return View::make('reports.fachirereport',
			['whole'=>$whole,
			'keys'=>$keys,
			'dept'=>$dept]);
	}

}