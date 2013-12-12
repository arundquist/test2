<?php
extract($_GET);
if ((!$year) AND (!$term) AND (!$prog)) {
	?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get">
Year (4 digits): <input type='text' name='year' size='20' /><br />
Program (3 or 4 letters (all caps) like 'PHYS' or 'MUS'). Use "ALL" for all: 
<input type='text' name='prog' size='4' /><br />
term: 
<select name='term'><br />

<option value = '11'>Fall
<option value = '12'>Winter
<option value = '13'>Spring
<option value = '15'>Summer
</select>
<br/>
<input type="checkbox" name="enrollment" value="True"> See enrollment data (slow - it takes ~1 minute if you choose "ALL")<br /> 
Enter a last name if you want to filter instructors: <input type='text' name='instructor' size = '20' /><br />
<input type="submit" />
</form>
<?php
	exit();
	}; //end of if for student id

	If ($term!=11){
		$year=$year-1;
	};

$fixedstring=$year.$term;
function fix($url) {
	$fixed=str_replace("hamschedule","https://piperline.hamline.edu/pls/prod/hamschedule",$url);
return $fixed;};

function findcrn($row) {
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
};

function findwordtitle($row) {
	$f=preg_match("/<B>(.*)<\/B>/", $row, $matches);
	If ($f>0) {
		$ret=$matches[1];
	} else {
		$ret=0;
	};
	RETURN $ret;
};

function findinstructor($row) {
	$f=preg_match('/300.*>(.+)<BR><\/TD>/', $row, $matches);
	If ($f>0) {
		$ret=$matches[1];
	} else {
		$ret=0;
	};
	RETURN $ret;
};

function finddaytime($row) {
	//$f=preg_match('/(([M,T,W,F][a-z]+day,)+[M,T,W,F][a-z]+day [0-9]{1,2}:[0-9]{2}[a,p]m-[0-9]{1,2}:[0-9]{2}[a,p]m)/', $row, $matches);
	$f=preg_match('/([M,T,W,F][a-z]+day[^\s]*)\s([0-9]{1,2}:[0-9]{2}[a,p]m-[0-9]{1,2}:[0-9]{2}[a,p]m)\s(.*?)<BR>/', $row, $matches);
	If ($f>0) {
		$ret=$matches;
	} else {
		$ret=0;
	};
	RETURN $ret;
};
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
};

//$m=finddaytime("<TD COLSPAN=\"4\">Class: February 7-May 8 Tuesday 2:30pm-4:30pm Robbins Science Center 106 <BR></TD>");
//print_r($m);
// https://piperline.hamline.edu/pls/prod/hamschedule.P_TermLevlPage?term_in=201113&levl_in=UG&key_in=&supress_others_in=N&format_in=L&sort_flag_in=S
// https://piperline.hamline.edu/pls/prod/hamschedule.P_TermLevlPage?term_in=201115&levl_in=UG&key_in=&supress_others_in=N&format_in=L&sort_flag_in=S
 $url="https://piperline.hamline.edu/pls/prod/hamschedule.P_TermLevlPage?term_in=$fixedstring&levl_in=UG&key_in=&supress_others_in=N&format_in=L&sort_flag_in=S";
$all=file($url,FILE_SKIP_EMPTY_LINES);
//print_r($all);

foreach ($all AS $row) {
	$crn=findcrn($row);
	If ($crn!==0){
		$key=$crn[2];
		$link[$key]="https://piperline.hamline.edu/pls/prod/$crn[0]";
	};
	if (!array_key_exists($key, $titles)){
		$t=findtitle($row);
		If ($t!==0) {
			$titles[$key]=$t;
		};
	};
	if (!array_key_exists($key, $wordtitles)){
		$t=findwordtitle($row);
		If ($t!==0) {
			$wordtitles[$key]=$t;
		};
	};
	if (!array_key_exists($key, $instructors)){
		$t=findinstructor($row);
		If ($t!==0) {
			$instructors[$key]=$t;
		};
	};
	if (!array_key_exists($key, $daytimes)){
		$t=finddaytime($row);
		If ($t!==0) {
			$days[$key]=$t[1];
			$times[$key]=$t[2];
			$rooms[$key]=$t[3];
		};
	};
}; 

echo "<table border=1><tr><th>crn</th><th>course</th><th>title</th><th>instructor</th><th>day</th><th>time</th><th>room</th><th>enrollment</th></tr>\n";
foreach ($titles AS $key=>$value) {
	
	$programarray = preg_split("/[\s,]+/", $value);
	$program=$programarray[0];
	if ((($program == $prog) OR ($prog=="ALL")) AND prof($instructors[$key])) {
	
	echo "<tr><td><a href='$link[$key]'>$key</a></td><td>$titles[$key]</td><td>$wordtitles[$key]</td><td>$instructors[$key]</td><td>$days[$key]</td><td>$times[$key]</td><td>$rooms[$key]</td>";
	if (ISSET($enrollment)) {
		echo "<td>";
		$all=file($link[$key],FILE_SKIP_EMPTY_LINES);
		$full=implode(" ", $all);
		$check=preg_match("/([0-9]{1,3} of [0-9]{1,3})/", $full, $matches);
		echo $matches[1];
		echo "</td>";
	} else {
		echo "<td></td>";
	};
	
	echo "</tr>\n";
	};
	};
echo "</table>"; 
/* 
echo "<h1>Piperline class schedule</h1>";

echo "<h2>Winter</h2>";
$url="https://piperline.hamline.edu/pls/prod/hamschedule.P_TermLevlPage?term_in=201113&levl_in=UG&key_in=&supress_others_in=N&format_in=L&sort_flag_in=S";
$all=file($url);
foreach ($all AS $key=>$row) {
	if (substr_count($row, "Class:") >0) {
		$deletelist[]=$key-2;
	};
};

foreach ($all AS $key=>$row) {
	If (!(in_array($key,$deletelist))) {
		echo $all[$key];
	};
};  */

//print_r($deletelist);



?>
