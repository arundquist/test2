@extends('layouts.main')

@section('navcomplete')
<li>{{HTML::linkAction('TermsController@index', "choose different term")}}</li>
@stop

@section('main')
@if (isset($title))
<h2>{{$title}}</h2>
@endif
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
