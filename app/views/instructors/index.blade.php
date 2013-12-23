@extends('layouts.main')

@section('main')
<ul class="list-inline">
@foreach ($instructors AS $instructor)
<li>{{HTML::linkAction('InstructorsController@show', "$instructor->name", $instructor->id)}}</li>
@endforeach
</ul>
@stop