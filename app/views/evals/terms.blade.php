@extends('layouts.main')

@section('main')


{{Form::open(['method'=>'post', 'action'=>['EvalsController@postTerms']])}}

{{Form::submit('submit')}}<br/>

@foreach (Session::get('terms') AS $key=>$value)
	{{Form::checkbox('terms[]', $key)}}
	{{Form::label($value)}}<br/>
@endforeach

{{Form::close()}}


@stop