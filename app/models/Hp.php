<?php

class Hp extends Eloquent {
	protected $guarded = array();

	public static $rules = array();
	
	public function courses()
	{
		return $this->belongsToMany('Course');
	}
}
