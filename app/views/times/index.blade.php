@extends('layouts.main')

@section('main')
<ul class="list-inline">
@foreach ($times AS $time)
<li>{{HTML::linkAction('TimesController@show', "{$time->day}:{$time->beginning}-{$time->end}", $time->id)}}</li>
@endforeach
</ul>
@stop