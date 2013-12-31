<?php

class DataController extends BaseController {
	
	public function original()
	{
		$input=Input::all();
		$key=99999;
		$titles=array();
		$wordtitles=array();
		$instructors=array();
		$daytimes=array();
		$link=array();
		$days=array();
		$year=$input["year"];
		$term=$input["term"];
		If ($term!=11){
			$year=$year-1;
			};
		$fixedstring=$year.$term;
		$url="https://piperline.hamline.edu/pls/prod/hamschedule.P_TermLevlPage?term_in=$fixedstring&levl_in=UG&key_in=&supress_others_in=N&format_in=L&sort_flag_in=S";
		//$url="http://localhost/spring2012.html";
		$all=file($url,FILE_SKIP_EMPTY_LINES);
		 foreach ($all AS $row) {
		 	 $crn=$this->findcrn($row);
		 	 If ($crn!==0){
		 	 	 $key=$crn[2];
		 	 	 $link[$key]="https://piperline.hamline.edu/pls/prod/$crn[0]";
		 	 };
		 	 if (!array_key_exists($key, $titles)){
		 	 	 $t=$this->findtitle($row);
		 	 	 If ($t!==0) {
		 	 	 	 $titles[$key]=$t;
		 	 	 };
		 	 };
		 	 if (!array_key_exists($key, $wordtitles)){
		 	 	 $t=$this->findwordtitle($row);
		 	 	 If ($t!==0) {
		 	 	 	 $wordtitles[$key]=$t;
		 	 	 };
		 	 };
		 	 if (!array_key_exists($key, $instructors)){
		 	 	 $t=$this->findinstructor($row);
		 	 	 If ($t!==0) {
		 	 	 	 $instructors[$key]=$t;
		 	 	 };
		 	 };
		 	 if (!array_key_exists($key, $daytimes)){
		 	 	 $t=$this->finddaytime($row);
		 	 	 If ($t!==0) {
		 	 	 	 $days[$key]=$t[1];
		 	 	 	 $times[$key]=$t[2];
		 	 	 	 $rooms[$key]=$t[3];
		 	 	 };
		 	 };
		 	 if (!array_key_exists($key,$days))
		 	 {
		 	 	 $days[$key]="none";
		 	 	 $times[$key]="none";
		 	 	 $rooms[$key]="none";
		 	 };
		 }; 
		 foreach ($link AS $thiskey=>$value) {
		 	 if (!array_key_exists($thiskey,$titles))
		 	 {
		 	 	 $titles[$thiskey]='';
		 	 };
		 	 $full[$thiskey]=array(
		 	 	 "link"=>$value,
		 	 	 "title"=>$titles[$thiskey],
		 	 	 "wordtitle"=>$wordtitles[$thiskey],
		 	 	 "instructors"=>$instructors[$thiskey],
		 	 	 "crn"=>$thiskey,
		 	 	 "days"=>$days[$thiskey],
		 	 	 "time"=>$times[$thiskey],
		 	 	 "room"=>$rooms[$thiskey]);
		 };
		 return View::make('originaltable', array("full"=>$full));
	}
	
	function fix($url) {
		$fixed=str_replace("hamschedule","https://piperline.hamline.edu/pls/prod/hamschedule",$url);
		return $fixed;}

public function findcrn($row) {
	$f=preg_match("/(hamschedule.*crn_in=([0-9]{5}))/", $row, $matches);
	If ($f>0) {
		$ret=$matches;
	} else {
		$ret=0;
	};
	RETURN $ret;
}

function findtitle($row) {
	$f=preg_match("/180.*>([A-Z][A-Z][A-Z][A-Z]* [0-9]{4}.*)<\/TD>/", $row, $matches);
	If ($f>0) {
		$ret=$matches[1];
	} else {
		$ret=0;
	};
	RETURN $ret;
}

function findwordtitle($row) {
	$f=preg_match("/<B>(.*)<\/B>/", $row, $matches);
	If ($f>0) {
		$ret=$matches[1];
	} else {
		$ret=0;
	};
	RETURN $ret;
}

function findinstructor($row) {
	$f=preg_match('/300.*>(.+)<BR><\/TD>/', $row, $matches);
	If ($f>0) {
		$ret=$matches[1];
	} else {
		$ret=0;
	};
	RETURN $ret;
}

function finddaytime($row) {
	//$f=preg_match('/(([M,T,W,F][a-z]+day,)+[M,T,W,F][a-z]+day [0-9]{1,2}:[0-9]{2}[a,p]m-[0-9]{1,2}:[0-9]{2}[a,p]m)/', $row, $matches);
	$f=preg_match('/([M,T,W,F][a-z]+day[^\s]*)\s([0-9]{1,2}:[0-9]{2}[a,p]m-[0-9]{1,2}:[0-9]{2}[a,p]m)\s(.*?)<BR>/', $row, $matches);
	If ($f>0) {
		$ret=$matches;
	} else {
		$ret=0;
	};
	RETURN $ret;
}
//$instructor="Rundquist";
function prof($inst) {
	global $instructor;
	
	$tmp=strpos($inst, $instructor);
	
	if ($instructor == "") {
		Return TRUE;
	} ELSE {
		if ($tmp === FALSE) {
			RETURN FALSE;
		} ELSE {
			RETURN TRUE;
		};
	};
}
	public function getcrns()
	{
		$url="http://localhost/spring2012.html";
		$all=file_get_contents($url,FILE_SKIP_EMPTY_LINES);
		$f=preg_match_all("/hamschedule.*crn_in=([0-9]{5})/", $all, $matches);
		$crns=$matches[1];
		return View::make('viewcrns', array("crns"=>$crns,
			"baselink"=>"https://piperline.hamline.edu/pls/prod/hamschedule.P_OneSingleCourse?term_in=201113&levl_in=UG&key_in=&format_in=L&sort_flag_in=S&supress_others_in=N&crn_in="));
	}
	
	public function getcrnsactual($year, $term)
	{
		$originalyear=$year;
		If ($term!=11){
			$year=$year-1;
			};
		$fixedstring=$year.$term;
		$url="https://piperline.hamline.edu/pls/prod/hamschedule.P_TermLevlPage?term_in=$fixedstring&levl_in=UG&key_in=&supress_others_in=N&format_in=L&sort_flag_in=S";
		
		$all=file_get_contents($url,FILE_SKIP_EMPTY_LINES);
		$f=preg_match_all("/hamschedule.*crn_in=([0-9]{5})/", $all, $matches);
		$crns=$matches[1];
		foreach ($crns AS $crn)
		{
			echo link_to_action('DataController@getsingle', $crn, $parameters = array($crn,$originalyear,$term), $attributes = array());
			echo "<br/>";
			$biglist[$crn]=$this->getsingleloop($crn,$originalyear,$term);
		};
		Return "done";
	}
	

	
	public function getalldata()
	{
		$url="http://localhost/singleclass.html";
		$all=file_get_contents($url,FILE_SKIP_EMPTY_LINES);
		$f=preg_match("/(table class=\"datadisplaytable\".*?<\/table>)/sm",$all,$matches);
		$g=preg_match_all("/<td.*?>.*?<p.*.?>(.*?)<\/p>.*?<\/td>/m",$matches[1],$matches2);
		$check=preg_match("/([0-9]{1,3}) of ([0-9]{1,3})/", $all, $matches3);
		$i=preg_match("/(Instructor\(s\).*?<\/tr>)/sm",$all,$matches4);
		$j=preg_match_all("/<a.*?>(.*?)</",$matches4[1],$matches5);
		$k=preg_match("/(Begin Date.*?<\/tr>.*?<\/tr>)/sm",$all,$matches6);
		$l=preg_match_all("/<td.*?>.*?<p.*.?>(.*?)<\/p>.*?<\/td>/m",$matches6[1],$matches7);
		$m=preg_match_all("/([A-Z][a-z]{2}?)/",$matches7[1][3],$matches8);
		$n=preg_match("/Course Description:.*?<td.*?><p.*?>(.*)?<\/p><\/td>/sm",$all,$matches9);
		$o=preg_match_all("/([A-Z])/",$matches2[1][4],$matches10);
		$allinfo=array(
			"enrollmentmax"=>$matches3[2],
			"enrollmentactual"=>$matches3[1],
			"dept"=>$matches2[1][1],
			"number"=>$matches2[1][2],
			"HP"=>$matches10[1],
			"title"=>$matches2[1][6],
			"instructors"=>$matches5[1],
			"days"=>$matches8[1],
			"timestart"=>$matches7[1][4],
			"timeend"=>$matches7[1][5],
			"building"=>$matches7[1][6],
			"room"=>$matches7[1][7],
			"description"=>$matches9[1]);
		
		return var_dump($allinfo);
	}
	
	public function getsingle($crn,$year,$term)
	{
		If ($term!=11){
			$year=$year-1;
			};
		$fixedstring=$year.$term;
		$url="https://piperline.hamline.edu/pls/prod/hamschedule.P_OneSingleCourse?term_in=$fixedstring&levl_in=UG&key_in=&format_in=L&sort_flag_in=S&supress_others_in=N&crn_in=$crn";
		
			
		$all=file_get_contents($url,FILE_SKIP_EMPTY_LINES);
		$f=preg_match("/(TABLE\s+CLASS=\"datadisplaytable\".*?<\/TABLE>)/sm",$all,$matches);
		$g=preg_match_all("/<TD.*?>.*?<p.*.?>(.*?)<\/TD>/m",$matches[1],$matches2);
		if (count($matches2[1])==6)
		{
			$hp=array();
			$cnt=5;
		} else {
			$o=preg_match_all("/([A-Z])/",$matches2[1][4],$matches10);
			$hp=$matches10[1];
			$cnt=6;
		};
		$check=preg_match("/([0-9]{1,3}) of ([0-9]{1,3})/", $all, $matches3);
		$i=preg_match("/(Instructor\(s\).*?<\/TR>)/sm",$all,$matches4);
		$j=preg_match_all("/([A-Za-z]+, [A-Za-z]+)/",$matches4[1],$matches5);
		$k=preg_match("/(Begin Date.*?<\/TR>.*?<\/TR>)/sm",$all,$matches6);
		$l=preg_match_all("/<TD.*?><p.*?>(.*?)<\/TD>/sm",$matches6[1],$matches7);
		$m=preg_match_all("/([A-Z][a-z]{2}?)/",$matches7[1][3],$matches8);
		$n=preg_match("/Course Description:.*?<TD.*?><p.*?>(.*?)<\/TD>/sm",$all,$matches9);
		
		$allinfo=array(
			"enrollmentmax"=>$matches3[2],
			"enrollmentactual"=>$matches3[1],
			"dept"=>$matches2[1][1],
			"number"=>$matches2[1][2],
			"HP"=>$hp,
			"title"=>$matches2[1][$cnt],
			"instructors"=>$matches5[1],
			"days"=>$matches8[1],
			"timestart"=>$matches7[1][4],
			"timeend"=>$matches7[1][5],
			"building"=>$matches7[1][6],
			"room"=>$matches7[1][7],
			"description"=>$matches9[1]);
		
		return var_dump($allinfo);
	}
	
	public function getsinglefromid($course_id)
	{
		$course=Course::findOrFail($course_id);
		$url=$course->url;
		$all=file_get_contents($url, FILE_SKIP_EMPTY_LINES);
		if (!strpos($all, "CANCELED"))
		{
			$f=preg_match("/(TABLE\s+CLASS=\"datadisplaytable\".*?<\/TABLE>)/sm",$all,$matches);
			$g=preg_match_all("/<TD.*?><p.*?>(.*?)<\/p><\/TD>/m",$matches[1],$matches2);
			if (count($matches2[1])==6)
			{
				$asindex=4;
			} else {
				$asindex=5;
			};
			if (strpos($all, "Area of Study"))
			{
				$as=preg_match_all("/([^,]+)/", $matches2[1][$asindex], $areamatches);
				$areas=array();
				if($as){$areas=$areamatches[1];};
				
				// now dump that into the database
				$areasids=array();
				foreach ($areas AS $area)
				{
					$area=trim($area);
					$areadb=Area::where('area', $area)->first();
					if ($areadb==Null)
					{
						$areadb=new Area;
						$areadb->area=$area;
						$areadb->save();
					};
					// now $areadb->id will be correct
					$areasids[]=$areadb->id;
				};
				$course->areas()->sync($areasids);
			} // closes loop that looks for "Areas of Study"
			
			$check=preg_match("/([0-9]{1,3}) of ([0-9]{1,3})/", $all, $matches3);
			$enrollmentmax="";
			$enrollment="";
			if ($check)
			{
				$enrollmentmax=$matches3[2];
				$enrollment=$matches3[1];
			};
			$course->enrollmentmax=$enrollmentmax;
			$course->enrollment=$enrollment;
			$course->enrollmentchecked="1";
			$course->save();
		} else {
			$course->enrollmentmax="0";
			$course->enrollment="0";
			$course->enrollmentchecked="1";
			$course->cancelled="1";
			$course->save();
		};
	}
	
	public function checkspeed()
	{
		$t=time();
		for ($i=0; $i<20; $i++)
		{
			$this->getsinglefromid(200);
		};
		$t2=time();
		$diff=$t2-$t;
		echo $diff;
	}
	
	public function grabenrollments()
	{
		$courses=Course::where('enrollmentchecked','0')->get();
		echo $courses->count();
		ini_set('max_execution_time', 300);
		$i=0;
		foreach ($courses AS $course)
		{
			$this->getsinglefromid($course->id);
			$i++;
		};
		echo $i;
	}
	
	public function getsingleloop($crn,$year,$term)
	{
		If ($term!=11){
			$year=$year-1;
			};
		$fixedstring=$year.$term;
		$url="https://piperline.hamline.edu/pls/prod/hamschedule.P_OneSingleCourse?term_in=$fixedstring&levl_in=UG&key_in=&format_in=L&sort_flag_in=S&supress_others_in=N&crn_in=$crn";
		
			
		$all=file_get_contents($url,FILE_SKIP_EMPTY_LINES);
		$f=preg_match("/(TABLE\s+CLASS=\"datadisplaytable\".*?<\/TABLE>)/sm",$all,$matches);
		$g=preg_match_all("/<TD.*?>.*?<p.*.?>(.*?)<\/TD>/m",$matches[1],$matches2);
		if (count($matches2[1])==6)
		{
			$hp=array();
			$cnt=5;
		} else {
			$o=preg_match_all("/([A-Z])/",$matches2[1][4],$matches10);
			$hp=$matches10[1];
			$cnt=6;
		};
		
		$check=preg_match("/([0-9]{1,3}) of ([0-9]{1,3})/", $all, $matches3);
		$i=preg_match("/(Instructor\(s\).*?<\/TR>)/sm",$all,$matches4);
		$j=preg_match_all("/([A-Za-z]+, [A-Za-z]+)/",$matches4[1],$matches5);
		$k=preg_match("/(Begin Date.*?<\/TR>.*?<\/TR>)/sm",$all,$matches6);
		$l=preg_match_all("/<TD.*?><p.*?>(.*?)<\/TD>/sm",$matches6[1],$matches7);
		$m=preg_match_all("/([A-Z][a-z]{2}?)/",$matches7[1][3],$matches8);
		$n=preg_match("/Course Description:.*?<TD.*?><p.*?>(.*?)<\/TD>/sm",$all,$matches9);
		
		$allinfo=array(
			"enrollmentmax"=>$matches3[2],
			"enrollmentactual"=>$matches3[1],
			"dept"=>$matches2[1][1],
			"number"=>$matches2[1][2],
			"HP"=>$hp,
			"title"=>$matches2[1][$cnt],
			"instructors"=>$matches5[1],
			"days"=>$matches8[1],
			"timestart"=>$matches7[1][4],
			"timeend"=>$matches7[1][5],
			"building"=>$matches7[1][6],
			"room"=>$matches7[1][7],
			"description"=>$matches9[1]);
		
		return $allinfo;
	}
	
	public function getalldetails($string)
	{
		$result=array(
			"crn"=>"",
			"dept"=>"",
			"num"=>"",
			"sec"=>"",
			"instructors"=>array(),
			"title"=>"",
			"credits"=>"",
			"description"=>"",
			"hps"=>array(),
			"days"=>array(),
			"starttime"=>"",
			"endtime"=>"",
			"building"=>"",
			"room"=>"");
		// this will grab all the various tds
		$g=preg_match_all("/<TD.*?>(.*?)<\/TD>/",$string,$matches2);
		// if the 5th one is "corequisites:" then you have to add
		// 2 to all the indices after that
		
		$add=0;
		if ($matches2[1][5]=="Corequisites:") 
		{
			$add=2;
		};
		// grab crn out of link
		$h=preg_match("/<a.*?>(.*?)</",$matches2[1][0],$crnmatch);
		$result["crn"]=$crnmatch[1];
		//grab dept and course and section number
		$h=preg_match("/([A-Z&]{3,4}) ([0-9L]{4,5})-(.*)/",$matches2[1][1],$deptmatch);
		$result["dept"]=$deptmatch[1];
		$result["num"]=$deptmatch[2];
		$result["sec"]=$deptmatch[3];
		
		// grab HP's
		$h=preg_match_all("/([A-Z]{1})/", $matches2[1][2],$hpmatch);
		if ($h){$result["hps"]=$hpmatch[1];};
		
		//grab instructors
		// see page 35 for notes on this
		//$h=preg_match_all("/([A-Za-z]*, [A-Za-z]*)/",$matches2[1][3],$instmatch);
		$h=preg_match_all("/(.*?)<BR>/", $matches2[1][3],$instmatch);
		if ($h){$result["instructors"]=$instmatch[1];};
		//grab title
		$h=preg_match("/<B>(.*)</", $matches2[1][5+$add], $titlematch);
		if($h){$result["title"]=$titlematch[1];};
		
		// grab credits
		$h=preg_match("/(.*)\sc/", $matches2[1][6+$add], $creditmatch);
		if($h){$result["credits"]=$creditmatch[1];};
		
		
		//grab desc
		$result["description"]=$matches2[1][7+$add];
		//grab days
		$h=preg_match("/([M,T,W,F][a-z]+day[^\s]*)\s([0-9]{1,2}:[0-9]{2}[a,p]m)-([0-9]{1,2}:[0-9]{2}[a,p]m)\s(.*?)<BR>/",$matches2[1][8+$add], $daytimeroom);
		if($h)
		{
			// grab days
			$h1=preg_match_all("/([M,T,W,F][a-z]+day)/",$daytimeroom[1],$days);
			if ($h1) {$result["days"]=$days[1];};
			// grab times
			$result["starttime"]=$daytimeroom[2];
			$result["endtime"]=$daytimeroom[3];
			// I'd like to grab the building and room separate
			$h2=preg_match("/(.*?)\s([A-Za-z0-9]+)\s$/", $daytimeroom[4],$buildroom);
			if($h2)
			{
				$result["building"]=$buildroom[1];
				$result["room"]=$buildroom[2];
			};
		};
		//$h=preg_match_all("/(Monday|Tuesday|Wednesday|Thursday|Friday)/",$matches2[1][8+$add],$daysmatch);
		//if($h){$result["days"]=array_slice($daysmatch[1],0,-1);};
		// get times
		//$h=preg_match_all("/([0-9]{1,2}:[0-9]{2}[ap]m)/", $matches2[1][8+$add],$timematches);
		
		return $result;
	}
	
	public function getalldetailstest($string)
	{
		$result=array(
			"crn"=>"",
			"dept"=>"",
			"num"=>"",
			"sec"=>"",
			"instructors"=>array(),
			"title"=>"",
			"credits"=>"",
			"description"=>"",
			"hps"=>array(),
			"days"=>array(),
			"starttime"=>"",
			"endtime"=>"",
			"building"=>"",
			"room"=>"");
		// this will grab all the various tds
		$g=preg_match_all("/<TD.*?>(.*?)<\/TD>/",$string,$matches2);
		// if the 5th one is "corequisites:" then you have to add
		// 2 to all the indices after that
		return var_dump($matches2[1][1]);
	}
	
	public function grabfromlist()
	{
		$url="https://piperline.hamline.edu/pls/prod/hamschedule.P_TermLevlPage?term_in=201313&levl_in=UG&key_in=&supress_others_in=N&format_in=L&sort_flag_in=S";
		$all=file_get_contents($url,FILE_SKIP_EMPTY_LINES);
		// this will grab each course section
		$f=preg_match_all("/(<TR><TD><a href.*?<HR)/sm",$all,$matches);
		foreach ($matches[1] AS $m)
		{
			$biglist[]=$this->getalldetails($m);
			
		};
		//Return View::make('showall',array("biglist"=>$biglist));
		ini_set('max_execution_time', 60);
		foreach ($biglist AS $single)
		{
			$this->saveone("2014","13", $single);
		};
	}
	
	public function grabterm($year, $season)
	{
		$sarray=["fall"=>"11",
			"winter"=>"12",
			"spring"=>"13",
			"summer"=>"15"];
		$season=$sarray[$season];
		$urlyear=$year;
		If ($season!=11){
			$urlyear=$year-1;
			};
		$fixedstring=$urlyear.$season;
		$url="https://piperline.hamline.edu/pls/prod/hamschedule.P_TermLevlPage?term_in=$fixedstring&levl_in=UG&key_in=&supress_others_in=N&format_in=L&sort_flag_in=S";
		$all=file_get_contents($url,FILE_SKIP_EMPTY_LINES);
		// this will grab each course section
		$f=preg_match_all("/(<TR><TD><a href.*?<HR WIDTH)/sm",$all,$matches);
		foreach ($matches[1] AS $m)
		{
			
			$biglist[]=$this->getalldetails($m);
			
		};
		//Return View::make('showall',array("biglist"=>$biglist));
		ini_set('max_execution_time', 120);
		foreach ($biglist AS $single)
		{
			$this->saveone($year,$season, $single);
		}; 
	}
	
	// function to take single class data and put in database
	
	public function saveone($year,$season,$list)
	{
		// see page 24 of notebook for the thoughts on this
		
		// first get the term id
		$timelist=array();
		foreach($list['days'] AS $day)
		{
			$timelist[]=['beginning'=>$list['starttime'],
					'end'=>$list['endtime'],
					'day'=>$day];
		};
		
		If ($season!=11){
			$year=$year-1;
			};
		$term=Term::where('ay','=',$year)
		-> where('season','=',$season)->first();
		if ($term==null)
		{
			// insert the  new term
			$term = new Term;
			$term->ay = $year;
			$term->season = $season;
			$term->save();
		};
		// now $term->id will be the term id either way
		
		// first get the dept id
		$dept=Dept::where('shortname','=',$list['dept'])->first();
		if ($dept==null)
		{
			// insert the  new dept
			$dept = new Dept;
			$dept->shortname = $list['dept'];
			$dept->save();
		};
		// now $dept->id will be the term id either way
		
		// first get the building id
		$building=Building::where('name','=',$list['building'])->first();
		if ($building==null)
		{
			// insert the  new term
			$building = new Building;
			$building->name = $list['building'];
			$building->save();
		};
		// now $building->id will be the term id either way
		
		// for "times" we need to do it as a loop, I guess
		$tids=array();
		foreach ($timelist AS $tinfo)
		{
			$time=Time::where('beginning', '=', $tinfo['beginning'])
			->where('end', '=', $tinfo['end'])
			->where('day', '=', $tinfo['day'])
			->first();
			if ($time==null)
			{
				$time=new Time;
				$time->beginning=$tinfo['beginning'];
				$time->end=$tinfo['end'];
				$time->day=$tinfo['day'];
				$time->save();
			};
			$tids[]=$time->id;
		};
		// now all ids are in $tids
		
		// hps are like times 
		// these aren't grabbed yet
		$hpids=array();
		foreach ($list['hps'] AS $letter)
		{
			$hp=Hp::where('letter', '=', $letter)->first();
			if ($hp==null)
			{
				$hp=new Hp;
				$hp->letter=$letter;
				$hp->save();
			};
			$hpids[]=$hp->id;
		}; 
		// now all ids are in $hpids
		
		// now the room, use the building id from above
		$room=Room::where('building_id', '=', $building->id)
		->where('number','=',$list['room'])->first();
		if ($room==null)
		{
			$room=new Room;
			$room->building_id=$building->id;
			$room->number=$list['room'];
			$room->save();
		}
		// now room_id is just $room->id
		
		// now instructors. be careful because there's a
		// many-to-many between instructors and departments
		// I guess we're just doing the name existence here
		// but I think that the connection, if it doesn't exist,
		// between dept and instructor, should be set.
		$instructorids=array();
		foreach ($list['instructors'] AS $name)
		{
			$instructor=Instructor::where('name', '=', $name)->first();
			if ($instructor==null)
			{
				$instructor=new Instructor;
				$instructor->name=$name;
				$instructor->save();
			};
			$instructorids[]=$instructor->id;
		};
		// now all ids are in $instructorids
		
		// now check if crn exists to determine if this is 
		// update or create
		
		$course=Course::where('crn', '=', $list['crn'])->first();
		if ($course == null)
		{
			$course=new Course;
		};
		// now set everything and then use push, I guess
		$course->crn= $list['crn'];
		$course->term_id=$term->id;
		$course->dept_id=$dept->id;
		$course->number=$list['num'];
		$course->section=$list['sec'];
		$course->room_id=$room->id;
		$course->title=$list['title'];
		$course->description=$list['description'];
		$course->credits=$list['credits'];
		$course->save();
		// now we do the related models
		$course->instructors()->sync($instructorids);
		$course->times()->sync($tids);
		$course->hps()->sync($hpids);
		// that should do it!
		$course->save();
	}
	
	public function crndetails($crn)
	{
		$course=Course::with(array('term', 'areas'))
			->where('crn',$crn)->first();
		echo "<pre>";
		echo var_dump($course);
		echo "</pre>";
	}
	
	public function clearenrollment($term_id)
	{
		$success=DB::table('courses')
			->where('term_id', $term_id)
			->update(array('enrollmentchecked' => 0));
		 $queries = DB::getQueryLog();
		 RETURN var_dump($queries);
	}
}
