{{Form::open(array('action'=>'DataController@original'))}}
Year (4 digits) {{Form::text('year')}}
Program (all caps) {{Form::text('prog')}}
Term: {{Form::select('term', array('11' => 'Fall',
	'12' => 'Winter',
	'13' => 'Spring',
	'15' => 'Summer'))}}
Enrollment: {{Form::checkbox('enrollment', 'True')}}
Instructor filter: {{Form::text('instructor')}}
{{Form::submit('submit')}}
{{Form::close()}}
