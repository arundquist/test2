<?php

class Room extends Eloquent {
	protected $guarded = array();

	public static $rules = array();
	
	public function building()
	{
		return $this->belongsTo('Building');
	}
	
	public function courses()
	{
		return $this->hasMany('Course');
	}
}
