<?php

class Term extends Eloquent {
	protected $guarded = array();

	public static $rules = array();
	
	public function courses()
	{
		return $this->hasMany('Course');
	}
	
	public function getSeasonAttribute($value)
	{
		$sarray=["11"=>"fall",
			"12"=>"winter",
			"13"=>"spring",
			"15"=>"summer"];
		return $sarray[$value];
	}
	
	public function getAyAttribute($value)
	{
		if ($this->season=="fall")
		{
			return $value;
		};
		return $value+1;
	}
}
