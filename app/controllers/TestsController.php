<?php
//use Cogitatio\Sparkline\Sparkline;
class TestsController extends \BaseController {

	public function getUpdated()
	{
		$course=Course::where('crn',10560)
			->where('term_id',19)
			->first();
		dd($course);
	}

	public function getHeatmap()
	{
		$termid=\Session::get('term_id');
		$term=Term::findOrFail($termid);
		$times=DB::select("SELECT concat(t.day,': ', t.beginning,'-',t.end) AS ft, COUNT(c.id) as course_count, SUM(c.enrollment) AS totalenrollment
											FROM times t
											LEFT JOIN  course_time ct ON t.id = ct.time_id
											LEFT JOIN courses c ON c.id = ct.course_id AND c.term_id=$termid
											GROUP BY ft
											ORDER BY course_count DESC");
	//	dd($times);
		return View::make('times.heatmap',
			['times'=> $times,
		'term'=>$term]);
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

	public function getDeptseats($deptstring)
	{
		$dept=Dept::where('shortname',strtoupper($deptstring))->first();
		$cs=Helper::courselistwithmodel($dept);
		$facids=array();
		foreach ($cs AS $c)
		{
			foreach ($c->instructors AS $inst)
			{
				$facids["{$inst->name}"][]=$c->enrollment;

			};
		};
		ksort($facids);
		foreach ($facids AS $name=>$value)
		{
			echo "$name: ";
			$sum=array_sum($value);
			echo implode("+",$value);
			echo "= $sum<br/>";
		}


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
		/* testing the eval way
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
		*/
		$content=Helper::evalselects(['term_code'=>$fixedstring,
					'crev_code'=>'CLACE']);
		//dd($content);
		$find=preg_match('%<option VALUE="([0-9]+)">'.$fac.'%', $content, $match);
		//dd($find);
		if ($find)
			return $match[1];
		else
			return false;
	}

	public function crnevals($fixedstring, $crn, $rev)
	{
		/* testing new eval way
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
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $poststring);
		//curl_setopt($ch, CURLOPT_URL, $newurl);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
		$content = curl_exec($ch);
		*/

		$content=Helper::evalselects(['term_code'=>$fixedstring,
					'crev_code'=>'CLACE',
					'rev'=>$rev,
					'crn'=>$crn]);

		$string=$content;
		//dd($string);
		$completion=preg_match('%\('.$crn.'\)[^\(\)]+?(\([^\(\)]+?\))%',$content,$completematch);
		//dd($completion);
		if (!$completion)
			return null;
		$completeinfo=$completematch[1];
		$xlst=preg_match('%<TD CLASS="dddefault">XLST%',$string);
		if ($xlst)
			$start = 4;
		else
			$start=5;
		$startstring='<th CLASS="ddlabel" scope="row" >VALUE</th>
<th CLASS="ddlabel" scope="row" colspan="4">.*?</th>
<th CLASS="ddlabel" scope="row" >NUM</th>';
		$endstring='<td CLASS="dddefault"><B>MAX:';
		$qq=preg_match_all('%'.$startstring.'(.*?)(?=<th)%s', $string, $qmatch);
		$scores=array();
		$comments=array();
		foreach ($qmatch[1] AS $key=>$m)
		{
			$qs=preg_match_all('%<td CLASS="dddefault">[0-9].*?<td CLASS="dddefault">\s*?([0-9]+)%s', $m,$matches);
			$scores[$key]=$matches[1];
			$cm=preg_match_all('%<td CLASS="dddefault"colspan="6">(.*?)</td>%s', $m, $matches);
			if ($cm)
			{
				$comments[$key]=$matches[1];
			} else {
				$comments[$key]=array();
			};
		};
		$scores=array_slice($scores,1,10);
		$comments=array_slice($comments,1,10);
		// do averages
		$avgs=array();
		//dd($scores);
		foreach ($scores AS $key=>$row)
		{
			$avg=0;
			foreach ($row AS $val=>$column)
			{
				$avg+=($val+1)*($column);
			};
			if ($avg != 0)
				$avg=round($avg/array_sum($row),2);
			$avgs[$key]=$avg;
		};
		//dd($avgs);
		$overallavg=array_sum($avgs)/10;

		// now to grab the overall comments
		//$wholesection=preg_match('%<th CLASS="ddlabel" scope="row" colspan="6">Comments:</th>.*?<td CLASS="dddefault"colspan="6">(.*?)Comments made by the student in this section%s',
		//	$string,$wholesectionmatch);
		// the one commented out only works if there's at least one comment
		$wholesection=preg_match('%<th CLASS="ddlabel" scope="row" colspan="6">Comments:</th>(.*?)Comments made by the student in this section%s',
			$string,$wholesectionmatch);
		$oc=preg_match_all('%<td CLASS="dddefault"colspan="6">(.*?)</td>%s',
			$wholesectionmatch[1],$ocmatch);
		//$oc=preg_match_all('%<th CLASS="ddlabel" scope="row" colspan="6">Comments:</th>.*?<td CLASS="dddefault"colspan="6">(.*?)</td>%s',
		//	$string,$ocmatch);
		$overallcomments=array();
		if ($oc)
			$overallcomments=$ocmatch[1];

		$all=['scores'=>$scores,
			'avgs'=>$avgs,
			'comments'=>$comments,
			'completeinfo'=>$completeinfo,
			'overallcomments'=>$overallcomments,
			'overallavg'=>$overallavg];
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
		$xlst=preg_match('%<TD CLASS="dddefault">XLST%',$string);
		if ($xlst)
			$start = 4;
		else
			$start=5;
		$p=preg_match_all('%<TD CLASS="dddefault">[0-9].*?<TD CLASS="dddefault">\s*?([0-9]+)%s', $string,$matches);
		$scores=array_slice($matches[1],$start,70);
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

			//$p=preg_match_all('%<TD CLASS="dddefault">[0-9].*?<TD CLASS="dddefault">\s*?([0-9]+)%s', $string,$matches);
			//$scores[$crn]=array_slice($matches[1],-70,70);
			$xlst=preg_match('%<TD CLASS="dddefault">XLST%',$string);
			if ($xlst)
				$start = 4;
			else
				$start=5;
			$p=preg_match_all('%<TD CLASS="dddefault">[0-9].*?<TD CLASS="dddefault">\s*?([0-9]+)%s', $string,$matches);
			$scores[$crn]=array_slice($matches[1],$start,70);
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
		//$this->evallogin(Input::get('username'), Input::get('password'));
		Helper::evallogin(Input::get('username'), Input::get('password'));
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
		$wholenum=0;
		$wholeden=0;
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
					{
						// add to numerator and denominator
						// for the whole average
						foreach($evals['scores'] AS $qnum=>$row)
						{
							foreach ($row AS $val=>$num)
							{
								$wholenum+=($val+1)*$num;
								$wholeden+=$num;
							};
						};
						$all[$c->id]=['name'=>"{$c->dept->shortname}
									{$c->number}
									{$c->title}
									{$evals['completeinfo']}",
								'term'=>$termstring,
								'scores'=>$evals['scores'],
								'avgs'=>$evals['avgs'],
								'comments'=>$evals['comments'],
								'overallcomments'=>$evals['overallcomments'],
								'overallavg'=>$evals['overallavg']];
					}
				};
			};
		};
		$wholeavg=round($wholenum/$wholeden,2);
		//dd($wholeavg);
		return View::make('tests.fpcdisplay',
			['fac'=>$facmod,
			'all'=>$all,
			'questions'=>$questions,
			'wholeavg'=>$wholeavg,
			'totalvotes'=>$wholeden]);
	}

	public function spark($vals)
	{

		//$vals=[4,5,32,2,0,6,7];
		$max=max($vals);
		$h=25;
		$w=35;
		$d=$w/7;
		$ret= "<svg width='$w' height='$h'>";
		$ret.= "<polyline points=\"0,$h ";
		foreach ($vals AS $key=>$val)
		{
			$x=$key/7*$w;
			$y=($max-$val)*$h/$max;
			$val=$val*10;
			$x2=$x+$d;
			$ret.= "$x,$y $x2,$y ";
		};
		$ret.= $w.','.$h.'" style="fill:red;stroke:red;stroke-width:2" /></svg>';
		echo $ret;
    	}

    	public function getTestspark()
    	{
    		return Helper::spark([34,23,54,32,19,24,40]);
    	}

    	public function getAddclass($id)
    	{
    		$class=Course::findOrFail($id);
    		Session::push('user.classes', $id);
    		Return Redirect::back();
    	}

    	public function getDeleteclass($id)
    	{
    		$class=Course::findOrFail($id);
    		Session::put('user.classes', array_diff(Session::get('user.classes'), [$id]));
    		Return Redirect::back();
    	}

    	public function getClearclasses()
    	{
    		Session::forget('user.classes');
    		Return Redirect::to('/');
    	}

    	public function getShowmine()
    	{
    		$ids=array_unique(Session::get('user.classes'));
    		$cs=Course::whereIn('id',$ids)->get();
    		$cs->load('instructors', 'hps','rooms.building','dept','times', 'areas','term');
    		$title="Your chosen courses ";
    		$title.="Click on the class title to remove it from this list. ";
    		$title.=link_to_action('TestsController@getClearclasses', 'Delete all');
		return View::make('courses.index')
			->with('courses',$cs)
			->with('title',$title);
	}

	public function getCheckclasses()
	{
		return dd(Session::get('user.classes'));
	}

	public function getHphistory($letter)
	{
		$terms=Term::orderBy('ay','DESC')
			->orderBy('season','DESC')
			->get();
		$hpmodel=Hp::with('courses')->where('letter',$letter)->first();
		$info=array();
		echo "<ul>";
		foreach ($terms AS $term)
		{
			$courses=$hpmodel->courses()->where('term_id',$term->id)->sum('enrollment');
			$info[$term->id]=$courses;
			echo "<li>{$term->ay} {$term->season}: $courses";
		};
		echo "</li>";


	}

	public function getPietest($dept_id)
	{
		$array=["hi"=>2,
			"there"	=>	3,
			"sjdklfj"=>	4];
		$dept=Dept::findOrFail($dept_id);
		$courses=$dept->courses()
			->with('instructors')
			->where('term_id', Session::get('term_id'))
			->get();
		$array=array();
		$values=array();
		foreach ($courses AS $course)
		{
			$facname=$course->instructors()->first()->name;
			if (array_key_exists($facname, $values))
				$values[$facname]+=$course->enrollment;
			else
				$values[$facname]=$course->enrollment;
		};
		$array=$values;
		ksort($array);
		$title="Faculty student load for {$dept->shortname}";
		Return View::make('tests.pie',[
			"array"=>$array,
			"title"=>$title]);
	}

	public function getPietestcaps($dept_id)
	{
		$array=["hi"=>2,
			"there"	=>	3,
			"sjdklfj"=>	4];
		$dept=Dept::findOrFail($dept_id);
		$courses=$dept->courses()
			->with('instructors')
			->where('term_id', Session::get('term_id'))
			->get();
		$array=array();
		$values=array();
		foreach ($courses AS $course)
		{
			$facname=$course->instructors()->first()->name;
			if (array_key_exists($facname, $values))
				$values[$facname]+=$course->enrollmentmax;
			else
				$values[$facname]=$course->enrollmentmax;
		};
		$array=$values;
		ksort($array);
		$title="Faculty student load for {$dept->shortname}";
		Return View::make('tests.pie',[
			"array"=>$array,
			"title"=>$title]);
	}

	public function getHpbydepartment($letter)
	{
		$hp=Hp::where('letter', $letter)->first();
		$courses=$hp->courses()
			->with('dept')
			->where('term_id', Session::get('term_id'))
			->get();
		$array=array();
		foreach ($courses AS $course)
		{
			$key=$course->dept->shortname;
			if (array_key_exists($key, $array))
				$array[$key]+=$course->enrollment*$course->credits/4;
			else
				$array[$key]=$course->enrollment*$course->credits/4;
		};
		ksort($array);
		$title="Department breakdown of enrollment for $letter";
		Return View::make('tests.pie',[
			"array"=>$array,
			"title"=>$title]);
	}

	public function getSvg()
	{
		$svg="<svg xmlns='http://www.w3.org/2000/svg' version='1.1' width='35' height='25'><polyline points='0,25 0,18.75 5,18.75 5,12.5 10,12.5 10,6.25 15,6.25 15,18.75 20,18.75 20,6.25 25,6.25 25,12.5 30,12.5 30,0 35,0 35,25' style='fill:red;stroke:red;stroke-width:1' /></svg>";
		$svg2='<svg xmlns="http://www.w3.org/2000/svg" version="1.1">
  <circle cx="100" cy="100" r="25" stroke="black" stroke-width="1" fill="green"/>
</svg>';
		$b64svg=base64_encode($svg2);
		$u=urlencode($svg);
		echo "hi there";
		$img1="<img width='100' height='100' src='data:image/svg+xml;foo=bar,$u'/>";
		$img2="<img width='200' height='200' src='data:image/svg+xml;foo=bar,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20version%3D%221.1%22%3E%0A%20%20%3Ccircle%20cx%3D%22100%22%20cy%3D%22100%22%20r%3D%2225%22%20stroke%3D%22black%22%20stroke-width%3D%221%22%20fill%3D%22green%22%2F%3E%0A%3C%2Fsvg%3E%0A' />";


		echo $img1;
		echo $img2;
		echo $svg2;
	}

	public function getAllclasses($term_id)
	{
		$cs=Course::where("term_id",'=',$term_id)
    	    		->where("cancelled",0)->get();
    	    	$cs->load('rooms.building','times');
    	    	$all=array();
    	    	foreach ($cs AS $c)
    	    	{
    	    		foreach ($c->times AS $time)
    	    			$all[]=[$c->enrollment, $time->day,$time->beginning, $time->end];
    	    	};
    	    	return Response::json($all);
    	}

    	public function fixtime($timestring)
    	{
    		$match = preg_match('/(\d+):(\d+)(a|p)m/', $timestring, $matches);
    		//dd($matches);
    		if ($matches[3]=='p' && $matches[1]!=12)
    			$val=($matches[1]+12)*60+$matches[2];
    		else
    			$val=$matches[1]*60+$matches[2];
    		return($val);
    	}

    	public function getTermtimeplots($term_id)
    	{
    		$term=Term::findOrFail($term_id);
    		$title="{$term->season} {$term->ay}";
    		$cs=Course::where("term_id",'=',$term_id)
    	    		->where("cancelled",0)->get();
    	    	$cs->load('times');
    	    	$all=array();
    	    	foreach ($cs AS $c)
    	    	{
    	    		foreach ($c->times AS $time)
    	    		{
    	    			$all[$time->day][]=[$this->fixtime($time->beginning), $c->enrollment];
    	    			//$tmp=$c->enrollment;
    	    			$all[$time->day][]=[$this->fixtime($time->end),-$c->enrollment];
    	    		};
    	    	};
    	    	$summed=array();
    	    	$allsummed=array();
    	    	foreach ($all AS $key=>$day)
    	    	{
    	    		$summed=array();
    	    		foreach ($day AS $value)
    	    		{
    	    			if(!isset($summed[$value[0]]))
    	    				$summed[$value[0]]=0;
    	    			$summed[$value[0]]+=$value[1];
    	    		};
    	    		ksort($summed);
    	    		$tmp=0;
    	    		$rollup=array();
    	    		foreach ($summed AS $key2=>$value)
    	    		{
    	    			$tmp+=$value;
    	    			$rollup[$key2]=$tmp;
    	    		};
    	    		$allsummed[$key]=$rollup;
    	    	};
    	    	$MWF=["Monday"=>$allsummed["Monday"],
    	    		"Wednesday"=>$allsummed["Wednesday"],
    	    		"Friday"=>$allsummed["Friday"]];
    	    	$TR=["Tuesday"=>$allsummed["Tuesday"],
    	    		"Thursday"=>$allsummed["Thursday"]];
    	    	Return View::make('times.timecharts',[
			"MWF"=>$MWF,
			"TR"=>$TR,
			"title"=>$title]);

    	}

    	public function getModtimeplots($model, $id)
    	{
    		//$cs=Course::where("term_id",'=',$term_id)
    	    	//	->where("cancelled",0)->get();
    	    	$term=Term::findOrFail(\Session::get('term_id'));
    	    	switch ($model)
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

    	    	$model=ucwords(substr($model,0,-1));

    	    	$mod=$model::findOrFail($id);
    	    	$cs=$mod->courses()->where("term_id",'=', Session::get('term_id'))
    	    		->where("cancelled",0)->get();
    	    	$cs->load('times');
    	    	$all=array();
    	    	foreach ($cs AS $c)
    	    	{
    	    		foreach ($c->times AS $time)
    	    		{
    	    			$all[$time->day][]=[$this->fixtime($time->beginning), $c->enrollment];
    	    			//$tmp=$c->enrollment;
    	    			$all[$time->day][]=[$this->fixtime($time->end),-$c->enrollment];
    	    		};
    	    	};
    	    	$summed=array();
    	    	$allsummed=array();
    	    	foreach ($all AS $key=>$day)
    	    	{
    	    		$summed=array();
    	    		foreach ($day AS $value)
    	    		{
    	    			if(!isset($summed[$value[0]]))
    	    				$summed[$value[0]]=0;
    	    			$summed[$value[0]]+=$value[1];
    	    		};
    	    		ksort($summed);
    	    		$tmp=0;
    	    		$rollup=array();
    	    		foreach ($summed AS $key2=>$value)
    	    		{
    	    			$tmp+=$value;
    	    			$rollup[$key2]=$tmp;
    	    		};
    	    		$allsummed[$key]=$rollup;
    	    	};
    	    	$MWF=["Monday"=>$allsummed["Monday"],
    	    		"Wednesday"=>$allsummed["Wednesday"],
    	    		"Friday"=>$allsummed["Friday"]];
    	    	$TR=["Tuesday"=>$allsummed["Tuesday"],
    	    		"Thursday"=>$allsummed["Thursday"]];

    	    	Return View::make('times.timecharts',[
			"MWF"=>$MWF,
			"TR"=>$TR,
			"title"=>$title]);

    	}

			public function getLowenrolled(){
				$cs=Course::join('depts as de','de.id','=','dept_id')
					->where("term_id",'=',\Session::get('term_id'))
					->where("cancelled",0)
					->where("enrollment","<", 10)
					->orderby('de.shortname')
					->orderby("number", "ASC")
					->orderby("section","ASC")
					->get();
				$cs->load('instructors', 'hps','rooms.building','dept','times', 'areas','term');
				return View::make('courses.index')
					->with('courses',$cs)
					->with('title',"low enrolled");
			}


}
