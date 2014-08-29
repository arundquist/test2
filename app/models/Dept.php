<?php

class Dept extends Eloquent {
	protected $guarded = array();

	public static $rules = array();
	
	public function courses()
	{
		return $this->hasMany('Course');
	}
	
	public function getDeptAttribute()
	{
		return $this->shortname;
	}
	
	public function scopeMysort($query)
	{
		return $query->orderBy('shortname');
	}
}
