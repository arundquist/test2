<?php

class Time extends Eloquent {
	protected $guarded = array();

	public static $rules = array();
	
	public function courses()
	{
		return $this->belongsToMany('Course');
	}
	
	public function getSingleletterAttribute()
	{
		$day=$this->day;
		If ($day != 'Thursday')
		{
			return substr($day, 0, 1);
		};
		return 'R';
	}
}
