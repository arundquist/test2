@extends('layouts.main')

@section('navcomplete')
<li>{{HTML::linkAction('TermsController@index', "choose different term")}}</li>
<li>{{HTML::linkAction('TimesController@makecalendar', "google calendar", array(Request::segment(1), Request::segment(2),Session::get('term_id')))}}</li>
@stop

@section('main')

<table class="table-striped table-bordered">
<thead>
<tr>
@foreach(array_keys($all[0]) AS $key)
<th>{{$key}}</th>
@endforeach
</tr>
</thead>
@foreach($all AS $course)
<tr>
@foreach($course AS $thing)
<td>{{$thing}}</td>
@endforeach
</tr>
@endforeach
</table>

@stop
