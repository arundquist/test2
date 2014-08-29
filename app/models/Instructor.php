<?php

class Instructor extends Eloquent {
	protected $guarded = array();

	public static $rules = array();
	
	public function courses()
	{
		return $this->belongsToMany('Course');
	}
	
	public function getInstructorAttribute()
	{
		return $this->name;
	}
	
	public function scopeMysort($query)
	{
		return $query->orderBy('name');
	}
	
	
	
	
}
