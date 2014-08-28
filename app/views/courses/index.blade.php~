@extends('layouts.main')

@section('navcomplete')
<li>{{HTML::linkAction('TermsController@index', "choose different term")}}</li>
<li>{{HTML::linkAction('TimesController@makecalendar', "google calendar", array(Request::segment(1), Request::segment(2),Session::get('term_id')))}}</li>
@stop

@section('main')
@if (isset($title))
<h2>{{$title}}</h2>
@endif
<table class="table-striped table-bordered">
<thead>
<tr>
<th>term</th>
<th>crn</th>
<th>Dept</th>
<th>Number</th>
<th>section</th>
<th>title</th>
<th>credits</th>
<th>enrollment</th>
<th>Hamline Plan</th>
<th>Area(s) of study</th>
<th>instructors</th>
<th>where</th>
<th>when</th>
<th>Prereqs</th>
</tr>
</thead>
@foreach($courses AS $course)
<tr>
<td>{{$course->term->season}} {{$course->term->ay}}</td>
<td>{{$course->crn}}</td>
<td>{{HTML::linkAction('DeptsController@show', "{$course->dept->shortname}", $course->dept->id)}}</td>
<td>{{$course->number}}</td>
<td>{{$course->section}}</td>
<td>{{$course->title}}</td>
<td>{{$course->credits}}</td>
<td>{{HTML::linkRoute('enrollment', "{$course->enrollment} of {$course->enrollmentmax}", array($course->dept->shortname,$course->number))}}</br>
{{HTML::linkAction('DataController@updatebyid',"{$course->updated_at->diffForHumans()}", $course->id)}}
</td>
<td>
@foreach($course->hps AS $hp)
{{HTML::linkAction('HpsController@show',"{$hp->letter}", $hp->id)}},
@endforeach
</td>
<td>
@foreach($course->areas AS $area)
{{HTML::linkAction('AreasController@show', "{$area->area}", $area->id)}}, 
@endforeach
</td>
<td>
@foreach($course->instructors AS $instructor)
{{HTML::linkAction('InstructorsController@show', "$instructor->name", $instructor->id)}}<br/>
@endforeach
</td>

<td>
@foreach ($course->rooms AS $room)
	{{$room->building->name}} {{HTML::linkAction('RoomsController@show',"{$room->number}", $room->id)}}<br/>
@endforeach
</td>

<td>
@foreach($course->times AS $time)
{{link_to_action('TimesController@show', "{$time->singleletter}: {$time->beginning}-{$time->end}", $time->id)}}<br/>
@endforeach
</td>

<td>
{{$course->prereqs}}
</td>

@endforeach
</tr>
</table>
@stop




