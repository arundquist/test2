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
		//dd($mods);
		//$mods->load('courses');
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
		foreach ($countlist AS $modid=>$count)
		{
			echo "<tr><td>".link_to_action($model."sController@show", $count['what'], [$modid])."</td><td>{$count['count']}</td></tr>";
		};
		echo "</table>";
	}
	
	public function getPiperline($course_id)
	{
		return View::make('tests.piperline')
			->with('course_id', $course_id);
	}
	
	public function postPiperline($course_id)
	{
		$course=Course::findOrFail($course_id);
		$fac=$course->instructors->first()->name;
		
		$year=$course->term->ay;
		$season=$course->term->season;
		$sarray=["fall"=>"11",
			"winter"=>"12",
			"spring"=>"13",
			"summer"=>"15"];
		$season=$sarray[$season];
		If ($season!=11){
			$year=$year-1;
			};
		$fixedstring=$year.$season;
		$crn=$course->crn;
		
		
		$username=Input::get('username');
		$password=Input::get('password');
		$url="https://piperline.hamline.edu/pls/prod/twbkwbis.P_ValLogin";
		
		
		//$username = 'myuser';
		//$password = 'mypass';
		$loginUrl = $url;
		 
		//init curl
		$ch = curl_init();
		 
		//Set the URL to work with
		curl_setopt($ch, CURLOPT_URL, $loginUrl);
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		 
		// ENABLE HTTP POST
		curl_setopt($ch, CURLOPT_POST, 1);
		 
		//Set the post parameters
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'sid='.$username.'&PIN='.$password);
		 
		//Handle cookies for the login
		//curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEFILE, storage_path() . "/cookies.txt");
		 curl_setopt($ch, CURLOPT_COOKIEJAR, storage_path() . "/cookies.txt");
		
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,false);
		
		 
		//Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
		//not to print out the results of its query.
		//Instead, it will return the results as a string return value
		//from curl_exec() instead of the usual true/false.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 
		//execute the request (the login)
		$store = curl_exec($ch);
		
		$curl_info = curl_getinfo($ch);
		//dd($curl_info);
		
		//var_dump($curl_info);
		 
		//the login is now done and you can continue to get the
		//protected content.
		 
		//set the URL to the protected file
		$newurl="https://piperline.hamline.edu/pls/prod/hwskheva.P_EvalSummary";
		$poststring='term_code=201313&crev_code=CLACE&rev=626649&crn=38259';
		$newurl="https://piperline.hamline.edu/pls/prod/hwskheva.P_EvalView";
		
		// I want to see what I get if I don't give rev and crn
		
		//$poststring="term_code=$fixedstring&crev_code=CLACE&rev=626649&crn=$crn";
		$poststring="term_code=$fixedstring&crev_code=CLACE";
		curl_setopt($ch, CURLOPT_POSTFIELDS, $poststring);
		curl_setopt($ch, CURLOPT_URL, $newurl);
		//curl_setopt($ch, CURLOPT_POST, 0);
		//execute the request
		$content = curl_exec($ch);
		$curl_info = curl_getinfo($ch);
		//dd($content);
		$find=preg_match('%<OPTION VALUE="([0-9]+)">'.$fac.'%', $content, $match);
		$rev=$match[1];
		$poststring="term_code=$fixedstring&crev_code=CLACE&rev=$rev&crn=$crn";
		//dd($poststring);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $poststring);
		curl_setopt($ch, CURLOPT_URL, $newurl);
		curl_setopt($ch, CURLOPT_COOKIEJAR, storage_path() . "/cookies.txt");
		$content = curl_exec($ch);
		//var_dump($curl_info);
		//var_dump($content);
		$string=$content;
		//dd(curl_getinfo($ch));
		
		$questions=['communication',
			'organization',
			'environment',
			'active',
			'standards',
			'feedback',
			'assistance',
			'evaluation',
			'effective',
			'valuable'];
		$p=preg_match_all('%<TD CLASS="dddefault">[0-9].*?<TD CLASS="dddefault">\s*?([0-9]+)%s', $string,$matches);
		$scores=array_slice($matches[1],5,70);
		$betterarray=array();
		for ($i=0; $i<10; $i++)
		{
			for ($j=0; $j<7; $j++)
			{
				$betterarray[$i][$j]=$scores[$i*7+$j];
			};
		};
		$avgs=array();
		foreach ($betterarray AS $key=>$row)
		{
			$avg=0;
			foreach ($row AS $val=>$column)
			{
				$avg+=($val+1)*($column);
			};
			$avg=round($avg/array_sum($row),2);
			$avgs[$key]=$avg;
		};
		return View::make('tests.evaluation',
			['betterarray'=>$betterarray,
			'questions'=>$questions,
			'avgs'=>$avgs,
			'course'=>$course]);
		
	}

}