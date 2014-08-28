<?php

class Course extends Eloquent {
	protected $guarded = array();

	public static $rules = array();
	// I got this suggestion from
	// http://stackoverflow.com/questions/17232714/add-a-custom-attribute-to-a-laravel-eloquent-model-on-load
	// but it seems I don't need it.
	// I'm not sure when I do the array stuff
	protected $appends = array('prereqs');
	
	public function dept()
	{
		return $this->belongsTo('Dept');
	}
	
	public function instructors()
	{
		return $this->belongsToMany('Instructor');
	}
	
	public function times()
	{
		return $this->belongsToMany('Time');
	}
	
	public function rooms()
	{
		return $this->belongsToMany('Room');
	}
	
	public function hps()
	{
		return $this->belongsToMany('Hp');
	}
	
	public function areas()
	{
		return $this->belongsToMany('Area');
	}
	
	public function term()
	{
		return $this->belongsTo('Term');
	}
	
	// trying to understand accessors
	// this works, but see above for array stuff
	public function getPrereqsAttribute()
	{
		$desc=$this->description;
		$prereqs="None";
		$h=preg_match("/Prerequisites*:(.*?)(?:<|$)/", $desc, $matches);
		if ($h) {$prereqs=$matches[1];};
		return $prereqs;
	}
	
	public function getUrlAttribute()
	{
		$crn=$this->crn;
		$year=$this->term->ay;
		$season=$this->term->season;
		$sarray=["fall"=>"11",
			"winter"=>"12",
			"spring"=>"13",
			"summer"=>"15"];
		$season=$sarray[$season];
		If ($season!=11){
			$year=$year-1;
			};
		$fixedstring=$year.$season;
		$base="https://piperline.hamline.edu/pls/prod/hamschedule.P_OneSingleCourse?term_in=$fixedstring&levl_in=UG&key_in=&format_in=L&sort_flag_in=S&supress_others_in=N&crn_in=$crn";
		return $base;
	}
}
