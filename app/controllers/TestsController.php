<?php

class TestsController extends \BaseController {

	public function getUpdated($term_id)
	{
		$term=Term::findOrFail($term_id);
		$courses=$term->courses;
		dd($courses->lists('updated_at'));
	}

}