@extends('layouts.main')

@section('main')
<div>
Welcome. This is an experimental interface to Hamline's student evaluation
data. You put in your piperline login information, and the script logs you in and then
retrieves the evaluation data for this course (whose course "id" is the last
part of the url for this page). If you're not allowed access to that
course's evaluation data (which is true for all students and nearly all faculty) this
page should fail with all kinds of weird warnings. If you do have access (ie you're
the teacher of the course or you're a chair of the teacher etc) you should
see a formatted table of scores. Have fun!
</div>
{{Form::open(['method'=>'post', 'action'=>['TestsController@postPiperline',$course_id]])}}
{{Form::text('username', null, ['placeholder'=>'username'])}}<br/>
{{Form::password('password', null, ['placeholder'=>'password'])}}<br/>
{{Form::submit('submit')}}
{{Form::close()}}

@stop