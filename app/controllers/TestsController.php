<?php

class TestsController extends \BaseController {

	public function getUpdated($term_id)
	{
		$term=Term::findOrFail($term_id);
		$courses=$term->courses()
			->where('updated_at','<', date('Y-m-d'))
			->get();
		dd(count($courses));
	}

}