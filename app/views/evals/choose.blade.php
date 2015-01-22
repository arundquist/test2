@extends('layouts.main')

@section('main')

{{Form::open(['method'=>'post', 'action'=>['EvalsController@postChoose']])}}
@foreach ($selects AS $select)
	{{$select}}<br/>
@endforeach
{{Form::submit('submit')}}<br/>
{{Form::close()}}

@stop

