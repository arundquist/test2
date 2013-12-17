<?php namespace google;

class google
{
	public static function termstart($term_id)
	{
		$term=Term::findOrFail($term_id);
		$months=["fall"=>"0901",
			"winter"=>"0101",
			"spring"=>"0201"];
		Return "{$term->year}{$months[$term->season]}";
	}
}
