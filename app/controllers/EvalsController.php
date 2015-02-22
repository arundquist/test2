<?php

class EvalsController extends \BaseController {

	public function getIndex()
	{
		return View::make('evals.login');
	}
	
	public function postLogin()
	{
		Helper::evallogin(Input::get('username'), Input::get('password'));
		$selects=Helper::evalselects(array());
		// here save the current term into session
		// along with all other terms
		preg_match('%(<select name="term_code".*?</select>)%s', $selects, $match);
		preg_match('%Choose a Term.*<option selected="selected" VALUE="(.*?)"%s', $match[0],$selectmatch);
		Session::put('selectedterm', $selectmatch[1]);
		preg_match_all('%VALUE="(\d{6})">([S|W|F].*?)</%s', $match[0], $allterms);
		
		$terms=array();
		foreach ($allterms[1] AS $key=>$value)
		{
			$terms[$value]=$allterms[2][$key];
		};
		Session::put('terms', $terms);
		$crevselect=preg_match('%<select name="crev_code".*?>(.*?</select>)%s', $selects,$crevmatches);
		
		if ($crevselect)
		{
			// we need to have the user pick which evaluation
			$selecttext="<select name='crev_code'>$crevmatches[1]";
			return View::make('evals.crev',
				['selecttext'=>$selecttext]);
		};
		
		// it should only get here if there's no crev select
		$crevhidden=preg_match('%<input type="hidden" name="crev_code" value="([^"]+?)" />%s', $selects, $crevmatch);
		Session::put('crev_code', $crevmatch[1]);
		
		return Redirect::action('EvalsController@getTerms');
	}
	
	public function postCrev()
	{
		Session::put('crev_code', Input::get('crev_code'));
		return Redirect::action('EvalsController@getTerms');
	}
	
	public function getTerms()
	{
		$terms=Session::get('terms');
		return View::make('evals.terms');
	}
	
	public function postTerms()
	{
		// here we grab all the faculty in those 
		// terms and get it ready for the Rev stuff
		$nameslist=array();
		foreach (Input::get('terms') AS $term_id)
		{
			$content=Helper::evalselects(['term_code'=>$term_id, 'crev_code'=>Session::get('crev_code')]);
			$nameselect=preg_match('%<select name="rev".*?>(.*?)</select>%s',$content,$selectmatch);
			$names=preg_match_all('%<option VALUE="([0-9]+)">(.*?)</option>%s', $selectmatch[1], $namesmatch);
			foreach ($namesmatch[1] AS $key=>$value)
			{
				$nameslist=array_add($nameslist, $value, $namesmatch[2][$key]);
			};
	
		};
		Session::put('names', $nameslist);
		Session::put('evalterms', Input::get('terms'));
		return Redirect::action('EvalsController@getRev');	
	}
	
	public function getRev()
	{
		
		return View::make('evals.rev');
	}
	
	public function postRev()
	{
		//print_r(Session::all());
		//print_r(Input::all());
		$crnsarray=array();
		$crevcode=Session::get('crev_code');
		$rev=Input::get('name');
		$everything=array();
		$count=0;
		foreach (Session::get('evalterms') AS $term_code)
		{
			$content=Helper::evalselects(['term_code'=>$term_code,
				'crev_code'=>$crevcode,
				'rev'=>$rev]);
			//dd($content);
			// the preg here grabs any crns that have at least 1 eval
			
			// first see if there's a select with multiple crns
			// if so, grab 'em
			// if not, there's just one so you just need to grab it
			// and make it an array of 1 (so the rest works)
			
			$crnselectbool=preg_match('%<select name="crn".*?</select>%s', $content, $crnselectmatch);
			
			if ($crnselectbool)
			{
				// only here if there's multiple crns
				$crns=preg_match_all('%<option VALUE="(\d{5})">(.*?)</option>%s', 
					$crnselectmatch[0], $crnmatches);
			} else {
				// there's only one (or there was a problem)
				$string='%Course</th>
<td CLASS="dddefault">(.*?\((\d{5})\).*?)</td>%s';
				$crnsinglebool=preg_match($string, $content,$crnsinglematch);
				if(!$crnsinglebool)
					continue;
				$crnmatches=["single",[$crnsinglematch[2]],[$crnsinglematch[1]]];
			};
			
			// crnmatches[1] is the array of crns and [2] is the title with percent
			//I'm not sure I need to collect them all. Just grab the evals now
			//$crnsarray=array_merge($crnsarray, $crnmatches[1]);
			//dd($crnmatches);
			$allforthisterm=array();
			
			foreach ($crnmatches[1] AS $key=>$crn)
			{
				
				// here I make sure there's at least one person
				// filling something out
				$evalsbool=preg_match('&\([1-9]+.*?%\)&', $crnmatches[2][$key]);
				if (!$evalsbool)
					continue;
				$content=Helper::evalselects(['term_code'=>$term_code,
						'crev_code'=>$crevcode,
						'rev'=>$rev,
						'crn'=>$crn]);
				//dd($content);
				$startstring='<th CLASS="ddlabel" scope="row" >VALUE</th>
<th CLASS="ddlabel" scope="row" colspan="4">(.*?)</th>
<th CLASS="ddlabel" scope="row" >NUM</th>';
				$qq=preg_match_all('%'.$startstring.'(.*?)(?=<th)%s', $content, $qmatch);
				if (!$qq)
					continue;
				$count++;
				$allquestions=array();
				//dd($qmatch);
				foreach ($qmatch[1] AS $key2=>$question)
				{
					$allquestions[$key2]=['question'=>$question,
							'details'=>Helper::extractquestiondetails($qmatch[2][$key2]),
							'comments'=>Helper::extractquestioncomments($qmatch[2][$key2]),
							'average'=>Helper::extractquestionavg($qmatch[2][$key2])];
				};
				//dd($allquestions);
				$wholesection=preg_match('%<th CLASS="ddlabel" scope="row" colspan="6">Comments:</th>(.*?)Comments made by the student in this section%s',
					$content,$wholesectionmatch);
				$oc=preg_match_all('%<td CLASS="dddefault"colspan="6">(.*?)</td>%s',
					$wholesectionmatch[1],$ocmatch);
				//$oc=preg_match_all('%<th CLASS="ddlabel" scope="row" colspan="6">Comments:</th>.*?<td CLASS="dddefault"colspan="6">(.*?)</td>%s',
				//	$string,$ocmatch);
				$overallcomments=array();
				if ($oc)
					$overallcomments=$ocmatch[1];
				$find='%<th CLASS="ddlabel" scope="row" COLSPAN="5">Group Summary for questions of type "Strongly Dis/Strongly Agree":</TD></TR></th>
</tr>
<tr>
<td CLASS="dddefault">&nbsp;</td>
<td CLASS="dddefault"><B>AVG:\s*?(\d*\.?\d*)</B></td>
<td CLASS="dddefault"><B>STD:\s*?(\d*\.?\d*)</B></td>%s';
				$avgbool=preg_match($find, $content, $avgmatch);
				if ($avgbool)
					$avg=$avgmatch[1];
				else
					$avg=0;
				$allforthisterm[$key]=['title'=>$crnmatches[2][$key],
							'crn'=>$crn,
							'allquestions'=>$allquestions,
							'overallcomments'=>$overallcomments,
							'overallavg'=>$avg];
			};
			
			
			
			$everything[]=['term'=>$term_code,
				'evals'=>$allforthisterm,];
			
		};
		//dd($everything);
		//dd($count);
		return View::make('evals.display',
			['everything'=>$everything,
			'count'=>$count]);
	}
		
	
	public function postChoose()
	{
		$selects=Helper::evalselects(Input::all());
		//dd($selects);
		return View::make('evals.choose',
			['selects'=>$selects]);
	}
	
	

}