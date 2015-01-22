@extends('layouts.main')

@section('main')

{{Form::open(['method'=>'post', 'action'=>['EvalsController@postCrev']])}}
{{$selecttext}}<br/>
{{Form::submit('submit')}}<br/>
{{Form::close()}}

@stop