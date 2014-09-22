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
	
	public function getStartdateAttribute()
	{
		$months=["fall"=>"0901",
			"winter"=>"0101",
			"spring"=>"0201",
			"summer"=>"0601"];
		return "{$this->ay}{$months[$this->season]}";
	}
	
	public function getEnddateAttribute()
	{
		$months=["fall"=>"1215",
			"winter"=>"0131",
			"spring"=>"0515",
			"summer"=>"0815"];
		return "{$this->ay}{$months[$this->season]}";
	}
}
