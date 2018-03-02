@extends('layouts.main')


@section('main')
@if (isset($title))
<h2>{{$title}}</h2>
@endif
<table class="table-striped table-bordered">
<thead>
<tr>
<th>term</th>
<th>Number</th>
<th>title</th>
<th>description</th>
<th>instructor</th>
</tr>
</thead>
@foreach($courses AS $course)

<tr>
<td>{{$course->term->season}} {{$course->term->ay}}</td>
<td>{{$course->number}}</td>
<td>
	{{$course->title}}
</td>
<td>
	{{$course->description}}
</td>
<td>
	@foreach($course->instructors AS $instructor)
	{{$instructor->name}}
	@endforeach
</td>


@endforeach
</tr>
</table>
{{link_to_action('TestsController@getModtimeplots', "time plots for this list", [Request::segment(1), Request::segment(2)])}}
@stop
