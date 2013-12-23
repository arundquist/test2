<?php

class Building extends Eloquent {
	protected $guarded = array();

	public static $rules = array();
	
	public function rooms()
	{
		return $this->belongsToMany('Room');
	}
}
