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
		return View::make('tests.piperline',
			['course_id'=>$course_id,
			'path'=>'Piperline']);
	}
	
	public function evallogin($user,$pass)
	{
		$sesid=Session::GetId();
		$cookieFile=storage_path() . "/cookies".$sesid.".txt";
		
		
		
		$username=$user;
		$password=$pass;
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
		//curl_setopt($ch, CURLOPT_COOKIEFILE, storage_path() . "/cookies.txt");
		// curl_setopt($ch, CURLOPT_COOKIEJAR, storage_path() . "/cookies.txt");
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
		
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,false);
		
		 
		//Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
		//not to print out the results of its query.
		//Instead, it will return the results as a string return value
		//from curl_exec() instead of the usual true/false.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 
		//execute the request (the login)
		$store = curl_exec($ch);
		$store2=curl_exec($ch);
		//curl_close($ch);
	}
	
	public function verifyfac($fixedstring, $fac)
	{
		$sesid=Session::GetId();
		$cookieFile=storage_path() . "/cookies".$sesid.".txt";
		$newurl="https://piperline.hamline.edu/pls/prod/hwskheva.P_EvalView";
		$poststring="term_code=$fixedstring&crev_code=CLACE";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $poststring);
		curl_setopt($ch, CURLOPT_URL, $newurl);
		$content = curl_exec($ch);
		$find=preg_match('%<OPTION VALUE="([0-9]+)">'.$fac.'%', $content, $match);
		if ($find)
			return $match[1];
		else
			return false;
	}
	
	public function crnevals($fixedstring, $crn, $rev)
	{
		$sesid=Session::GetId();
		$cookieFile=storage_path() . "/cookies".$sesid.".txt";
		$poststring="term_code=$fixedstring&crev_code=CLACE&rev=$rev&crn=$crn";
		$newurl="https://piperline.hamline.edu/pls/prod/hwskheva.P_EvalView";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $poststring);
		curl_setopt($ch, CURLOPT_URL, $newurl);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $poststring);
		curl_setopt($ch, CURLOPT_URL, $newurl);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
		$content = curl_exec($ch);
		$string=$content;
		$completion=preg_match('%\('.$crn.'\)[^\(\)]+?(\([^\(\)]+?\))%',$content,$completematch);
		if (!$completion)
			return null;
		$completeinfo=$completematch[1];
		
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
		
		// grabbing comments. for now I'll just grab them all
		
		$cm=preg_match_all('%<TD CLASS="dddefault"colspan="6">(.*?)</TD>%s', $string, $matches);
		//dd($matches);
		$comments=$matches[1];
		$all=['scores'=>$scores,
			'betterarray'=>$betterarray,
			'avgs'=>$avgs,
			'comments'=>$comments,
			'completeinfo'=>$completeinfo];
		return $all;
	}
	
	public function postPiperline($course_id)
	{
		$sesid=Session::GetId();
		$cookieFile=storage_path() . "/cookies".$sesid.".txt";
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
		//curl_setopt($ch, CURLOPT_COOKIEFILE, storage_path() . "/cookies.txt");
		// curl_setopt($ch, CURLOPT_COOKIEJAR, storage_path() . "/cookies.txt");
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
		
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,false);
		
		 
		//Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
		//not to print out the results of its query.
		//Instead, it will return the results as a string return value
		//from curl_exec() instead of the usual true/false.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 
		//execute the request (the login)
		$store = curl_exec($ch);
		$store2 =curl_exec($ch);
		
		$curl_info = curl_getinfo($ch);
		//dd($curl_info);
		//dd('stopped after login');
		
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
		//dd('now second one sent');
		$curl_info = curl_getinfo($ch);
		//dd($content);
		$find=preg_match('%<OPTION VALUE="([0-9]+)">'.$fac.'%', $content, $match);
		$rev=$match[1];
		$poststring="term_code=$fixedstring&crev_code=CLACE&rev=$rev&crn=$crn";
		//dd($poststring);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $poststring);
		curl_setopt($ch, CURLOPT_URL, $newurl);
		//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
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
		$scores=array_slice($matches[1],-70,70);
		//dd($matches[1]);
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
		
		// grabbing comments. for now I'll just grab them all
		
		$cm=preg_match_all('%<TD CLASS="dddefault"colspan="6">(.*?)</TD>%s', $string, $matches);
		//dd($matches);
		return View::make('tests.evaluation',
			['betterarray'=>$betterarray,
			'questions'=>$questions,
			'avgs'=>$avgs,
			'course'=>$course,
			'comments'=>$matches[1]]);
		
	}
	
	public function getPiperlineall($course_id)
	{
		return View::make('tests.piperline',
			['course_id'=>$course_id,
			'path'=>"Piperlineall"]);
	}
	
	public function getFilecreate()
	{
		$sesid=Session::GetId();
		$cookieFile=storage_path() . "/cookies".$sesid.".txt";
		if(!file_exists($cookieFile)) 
		{
		    //dd($cookieFile);
		    $fh = fopen($cookieFile, "w");
		    fwrite($fh, "");
		    fclose($fh);
		    echo "did it";
		};
		echo "didn't do it";
	}
	
	public function postPiperlineall($instructor_id)
	{
		$facmodel=Instructor::findOrFail($instructor_id);
		//$course=Course::findOrFail($course_id);
		//$facmodel=$course->instructors->first();
		$sesid=Session::GetId();
		
		$cookieFile=storage_path() . "/cookies".$sesid.".txt";
		/* if(!file_exists($cookieFile)) 
		{
		    //dd($cookieFile);
			$fh = fopen($cookieFile, "w");
		    fwrite($fh, "");
		    fclose($fh);
		}; */
		$cs=Helper::courselistwithmodel($facmodel);
		$course=$cs->first();
		
		$classnames=$cs->lists('title','crn');
		
		
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
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
		 curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
		
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,false);
		
		 
		//Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
		//not to print out the results of its query.
		//Instead, it will return the results as a string return value
		//from curl_exec() instead of the usual true/false.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 
		//execute the request (the login)
		$store = curl_exec($ch);
		$store2 = curl_exec($ch);
		$poststring="term_code=$fixedstring&crev_code=CLACE";
		
		$curl_info = curl_getinfo($ch);
		//curl_close($ch);
		//sleep(2);
		//dd($curl_info);
		
		//var_dump($curl_info);
		 
		//the login is now done and you can continue to get the
		//protected content.
		 
		//set the URL to the protected file
		
		$newurl="https://piperline.hamline.edu/pls/prod/hwskheva.P_EvalView";
		
		// I want to see what I get if I don't give rev and crn
		
		//$poststring="term_code=$fixedstring&crev_code=CLACE&rev=626649&crn=$crn";
		$poststring="term_code=$fixedstring&crev_code=CLACE";
		$ch = curl_init();
		 
		//Set the URL to work with
		//curl_setopt($ch, CURLOPT_URL, $loginUrl);
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		 
		// ENABLE HTTP POST
		curl_setopt($ch, CURLOPT_POST, 1);
		 
		//Set the post parameters
		//curl_setopt($ch, CURLOPT_POSTFIELDS, 'sid='.$username.'&PIN='.$password);
		 
		//Handle cookies for the login
		//curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
		 //curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
		
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,false);
		
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, $poststring);
		curl_setopt($ch, CURLOPT_URL, $newurl);
		//curl_setopt($ch, CURLOPT_POST, 0);
		//execute the request
		$content = curl_exec($ch);
		$curl_info = curl_getinfo($ch);
		//dd($content);
		$find=preg_match('%<OPTION VALUE="([0-9]+)">'.$fac.'%', $content, $match);
		//dd($fac);
		$rev=$match[1];
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
		$scores=array();
		$betterarray=array();
		$avgs=array();
		$comments=array();
		$completeinfo=array();
		foreach ($cs->lists('crn') AS $crn)
		{
			$poststring="term_code=$fixedstring&crev_code=CLACE&rev=$rev&crn=$crn";
			//dd($poststring);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $poststring);
			curl_setopt($ch, CURLOPT_URL, $newurl);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
			$content = curl_exec($ch);
			//dd($content);
			//var_dump($curl_info);
			//var_dump($content);
			$string=$content;
			//dd(curl_getinfo($ch));
			$completion=preg_match('%\('.$crn.'\)[^\(\)]+?(\([^\(\)]+?\))%',$content,$completematch);
			if (!$completion)
				continue;
			$completeinfo[$crn]=$completematch[1];
			
			$p=preg_match_all('%<TD CLASS="dddefault">[0-9].*?<TD CLASS="dddefault">\s*?([0-9]+)%s', $string,$matches);
			$scores[$crn]=array_slice($matches[1],5,70);
			$betterarray[$crn]=array();
			for ($i=0; $i<10; $i++)
			{
				for ($j=0; $j<7; $j++)
				{
					$betterarray[$crn][$i][$j]=$scores[$crn][$i*7+$j];
				};
			};
			$avgs[$crn]=array();
			foreach ($betterarray[$crn] AS $key=>$row)
			{
				$avg=0;
				foreach ($row AS $val=>$column)
				{
					$avg+=($val+1)*($column);
				};
				$avg=round($avg/array_sum($row),2);
				$avgs[$crn][$key]=$avg;
			};
			
			// grabbing comments. for now I'll just grab them all
			
			$cm=preg_match_all('%<TD CLASS="dddefault"colspan="6">(.*?)</TD>%s', $string, $matches);
			//dd($matches);
			$comments[$crn]=$matches[1];
		};
		//dd($betterarray);
		return View::make('tests.evaluationall',
			['betterarray'=>$betterarray,
			'questions'=>$questions,
			'avgs'=>$avgs,
			'course'=>$course,
			'classnames'=>$classnames,
			'cs'=>$cs,
			'comments'=>$comments,
			'completeinfo'=>$completeinfo]);
		
	}
	
	public function getHps($dept_name)
	{
		//$dept=Dept::findOrFail($dept_id);
		$dept=Dept::where('shortname',strtoupper($dept_name))->firstOrFail();
		// get all the hp letters
		$hps=Hp::orderBy('letter')->get();
		$term=Term::findOrFail(Session::get('term_id'));
		$list=array();
		foreach ($hps AS $hp)
		{
			$list[$hp->letter]=$hp->courses()
				->where('term_id', Session::get('term_id'))
				->where('dept_id', $dept->id)
				->get();
			
		};
		return View::make('tests.hps',
			['list'=>$list,
			'term'=>$term,
			'dept'=>$dept]);
	}
	
	public function getFpc()
	{
		$instructors=Instructor::orderBy('name')->get();
		$terms=Term::orderBy('ay','DESC')
			->orderBy('season','DESC')
			->get();
		return View::make('tests.fpc',
			['instructors'=>$instructors,
			'terms'=>$terms]);
	}
	
	public function postFpc()
	{
		ini_set('max_execution_time', 300);
		$this->evallogin(Input::get('username'), Input::get('password'));
		$facmod=Instructor::findOrFail(Input::get('instructor'));
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
		$all=array();
		$names=array();
		foreach (Input::get('termids') AS $term_id => $code)
		{
			$rev=$this->verifyfac($code,$facmod->name);
			if ($rev != false)
			{
				$term=Term::findOrFail($term_id);
				$termstring="{$term->ay} {$term->season}";
				$cs=$facmod->courses()->where("term_id",$term_id)
					->where("cancelled",0)->get();
				foreach ($cs AS $c)
				{
					//$all[$term_id][$c->id]=$this->crnevals($code, $c->crn, $rev);
					//$names[$term_id][$c->id]=$c->title;
					$evals=$this->crnevals($code, $c->crn, $rev);
					if (!is_null($evals))
						$all[$c->id]=['name'=>"{$c->title} {$evals['completeinfo']}",
								'term'=>$termstring,
								'avgs'=>$evals['avgs']];
				};
			};
		};
		return View::make('tests.fpcdisplay',
			['fac'=>$facmod,
			'all'=>$all,
			'questions'=>$questions]);
	}

}