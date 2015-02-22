@extends('layouts.main')

@section('main')


{{Form::open(['method'=>'post', 'action'=>['EvalsController@postRev']])}}


{{Form::submit('submit')}}<br/>

{{Form::select('name', Session::get('names'))}}


{{Form::close()}}


@stop