<?php namespace Helpers;

class Helper {

    public static function helloWorld()
    {
        return 'Hello World';
    }
    
    public static function courselist($model, $id)
    {
    	    $mod=$model::findOrFail($id);
    	    $cs=$mod->courses()->where("term_id",'=',\Session::get('term_id'))
    	    	->where("cancelled",0)->get();
    	    $cs->load('instructors', 'hps','rooms.building','dept','times', 'areas','term');
    	    return $cs;
    }
    
    public static function courselistwithmodel($mod)
    {
    	    $cs=$mod->courses()->where("term_id",'=',\Session::get('term_id'))
    	    	->where("cancelled",0)->get();
    	    $cs->load('instructors', 'hps','rooms.building','dept','times', 'areas','term');
    	    return $cs;
    }
    
    public static function spark($vals)
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
		$ret.= $w.','.$h.'" style="fill:red;stroke:red;stroke-width:1" /></svg>';
		echo $ret;
    	}
    	
    public static function sparkflex($vals)
	{
		
		//$vals=[4,5,32,2,0,6,7];
		$max=max($vals);
		if ($max==0)
			return;
		$h=25;
		$w=35;
		$c=count($vals);
		$d=$w/$c;
		$ret= "<svg width='$w' height='$h'>";
		$ret.= "<polyline points=\"0,$h ";
		foreach ($vals AS $key=>$val)
		{
			$x=$key/$c*$w;
			$y=($max-$val)*$h/$max;
			$val=$val*10;
			$x2=$x+$d;
			$ret.= "$x,$y $x2,$y ";
		};
		$ret.= $w.','.$h.'" style="fill:red;stroke:red;stroke-width:1" /></svg>';
		echo $ret;
    	}
    	
    public static function sparkimg($vals)
	{
		// this doesn't seem to be working 1/6/15
		//$vals=[4,5,32,2,0,6,7];
		$max=max($vals);
		$h=25;
		$w=35;
		$d=$w/7;
		$ret= "<svg width='$w' height='$h'>";
		$ret.= "<polyline points='0,$h ";
		foreach ($vals AS $key=>$val)
		{
			$x=$key/7*$w;
			$y=($max-$val)*$h/$max;
			$val=$val*10;
			$x2=$x+$d;
			$ret.= "$x,$y $x2,$y ";
		};
		$ret.= "$w,$h' style='fill:red;stroke:red;stroke-width:1' /></svg>";
		//$ret64=base64_encode($ret);
		echo "<img width='$w' height='$h' src=\"data:image/svg,$ret\" />";
    	}
    	
    public static function evallogin($user,$pass)
	{
		$sesid=\Session::GetId();
		$cookieFile=storage_path() . "/cookies".$sesid.".txt";
		
		//dd($cookieFile);
		
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
	
	public static function evalselects($selects)
	{
		$sesid=\Session::GetId();
		$cookieFile=storage_path() . "/cookies".$sesid.".txt";
		$newurl="https://piperline.hamline.edu/pls/prod/hwskheva.P_EvalView";
		$ch = curl_init();
		$poststring="";
		foreach ($selects AS $key=>$value)
		{
			if($key!="_token")
				$poststring.="$key=$value&";
		};
		$poststring=rtrim($poststring,'&');
		//dd($poststring);
		//$poststring="term_code=$fixedstring&crev_code=CLACE";
		//$poststring.="crev_code=CLACE";
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $poststring);
		curl_setopt($ch, CURLOPT_URL, $newurl);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
		$content = curl_exec($ch);
		//dd(curl_getinfo($ch));
		//$selects=preg_match_all('%(<select.*?</select>)%s', $content, $matches);
		//dd($matches);
		return $content;
		
	}
	
	public static function extractquestiondetails($string)
	{
		//dd($string);
		$s='%<td CLASS="dddefault">(\d+)</td>\s<td CLASS="dddefault"colspan="4">(.*?)</td>\s<td CLASS="dddefault">\s*?(\d+)</td>%s';
		$dets=preg_match_all($s, $string, $matches);
		$qdetails=array();
		
		foreach ($matches[1] AS $key=>$value)
		{
			$qdetails[$key]=['text'=>$matches[2][$key],
					'value'=>$value,
					'votes'=>$matches[3][$key]];
		};
		
		return $qdetails;
		
	}
	
	public static function extractquestioncomments($string)
	{
		$comments=array();
		$cm=preg_match_all('%<td CLASS="dddefault"colspan="6">(.*?)</td>%s', $string, $cmatches);
			if ($cm)
			{
				$comments=$cmatches[1];
			} else {
				$comments=array();
			};
		return $comments;
	}
	
	public static function extractquestionavg($string)
	{
		$avgbool=preg_match('%<B>AVG:\s*?(\d*\.?\d*)</B>%s', $string, $avgmatch);
		//dd($avgmatch);
		if ($avgbool)
			$avg=$avgmatch[1];
		else
			$avg=0;
		return $avg;
	}
	
	public static function fixtermstring($string)
	{
		$sarray=["11"=>"fall",
			"12"=>"winter",
			"13"=>"spring",
			"15"=>"summer"];
		$season=substr($string,4,2);
		$year=substr($string,0,4);
		if ($season >11)
			$year++;
		return "$year $sarray[$season]";
	}
	
	public static function maketimeplot($allsummed)
    	{
    		$colors=["Monday"=>'black', 
    	    		"Tuesday"=>'red',
    	    		"Wednesday"=>'blue',
    	    		"Thursday"=>'green',
    	    		"Friday"=>'orange'];
    	    	echo '<svg height="300" width="900">';
    	    	$maxes=array();
    	    	foreach ($allsummed AS $list)
    	    		$maxes[]=max($list);
    	    	$absmax=max($maxes);
    	    	if ($absmax==0)
    	    		dd("no one is enrolled (yet?)");
    	    	$mult=275/$absmax;
  
  		foreach ($allsummed AS $daykey=>$day)
  		{
  			$m=max($day);
  			$m=2000;
  			$previous=275;
  			echo '<polyline points="';
  			foreach ($day AS $key=>$value)
  			{
  				$x=$key-480+30;
  				$y=275-$value*$mult;
  				echo "$x,$previous $x,$y ";
  				$previous=$y;
  			};
  			echo '" style="fill:none;stroke:';
  			echo $colors[$daykey];
  			echo ';stroke-width:3" />' ;
  			};
  		for ($x = 8; $x <= 21; $x++) {
  			$loc=($x-8)*60+30;
  			$name=$x;
  			if ($name>12)
  				$name-=12;
  			echo "<text x='$loc' y='300' fill='black'>$name</text>";
  		}
  		
  		for ($y = 0; $y <= 20; $y++) {
  			$loc=275-$y*100*$mult;
  			$name=$y*100;
  			
  			echo "<text x='0' y='$loc' fill='black'>$name</text>";
  		}
  		echo "<text x='0' y='10' fill='black'>$absmax</text>";
  		
  		echo '</svg>';
  	}
  	
  	public static function plotlegend()
  	{
  		$colors=["Monday"=>'black', 
    	    		"Tuesday"=>'red',
    	    		"Wednesday"=>'blue',
    	    		"Thursday"=>'green',
    	    		"Friday"=>'orange'];
    	    	echo "<b>";
    	    	foreach ($colors AS $day=>$color)
    	    		echo "<span style='color:$color'>$day </span>";
    	    	echo "</b>";
    	}
		
}
