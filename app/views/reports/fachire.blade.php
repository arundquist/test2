@extends('layouts.main')

@section('main')

<div>
This page should help you to fill out the chart required on the 
request for new hire form. Select your department and the relevant
academic years that you want data for.
</div>
<div class="row">

{{Form::open(['method'=>'post', 'action'=>['ReportsController@postFachire']])}}
<div class="col-md-4">
@foreach ($depts AS $deptid=>$dept)
	{{Form::radio('dept', $deptid)}} {{$dept}}<br/>
@endforeach
</div>
<div class="col-md-8">
@foreach ($ays AS $ay)
	{{Form::checkbox('ays[]', $ay)}} {{$ay}}<br/>
@endforeach
{{Form::submit()}}
</div>


@stop