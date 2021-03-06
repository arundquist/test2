@extends('layouts.main')

@section('main')

<div>
This page will provide most of the information to complete question #1 on the Full-Time Faculty Search Proposal form.  Select your department/program and the academic years for which you would like data, then click Submit.
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