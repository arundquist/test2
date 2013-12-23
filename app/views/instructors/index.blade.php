@extends('layouts.main')

@section('main')
<ul>
@foreach ($instructors AS $instructor)
<li>{{HTML::linkAction('InstructorsController@show', "$instructor->name", $instructor->id)}} ({{HTML::linkAction('InstructorsController@history', "history", $instructor->id)}})</li>
@endforeach
</ul>
@stop