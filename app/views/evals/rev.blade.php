@extends('layouts.main')

@section('main')
<div class="row">

{{Form::open(['method'=>'post', 'action'=>['EvalsController@postRev']])}}
<div class="col-md-6">
{{$revselect}}<br/>
{{Form::submit('submit')}}
</div>
<div class="col-md-6">
@foreach (Session::get('terms') AS $key=>$value)
	{{Form::checkbox('terms[]', $key)}}
	{{Form::label($value)}}<br/>
@endforeach
</div>
{{Form::close()}}
</div>

@stop