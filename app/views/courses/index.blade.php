@extends('layouts.main')
@section('main')
<h1>{{$courses[0]->term->ay}}: {{$courses[0]->term->season}}</h1>
<p>
{{HTML::linkAction('TermsController@index', "choose different term")}} 
{{HTML::linkAction('TimesController@makecalendar', "google calendar", array(Request::segment(1), Request::segment(2),Session::get('term_id')))}}
</p>
<table class="table-striped table-bordered">
<thead>
<tr>
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
<td>{{$course->crn}}</td>
<td>{{HTML::linkAction('DeptsController@show', "{$course->dept->shortname}", $course->dept->id)}}</td>
<td>{{$course->number}}</td>
<td>{{$course->section}}</td>
<td>{{$course->title}}</td>
<td>{{$course->credits}}</td>
<td>{{HTML::linkRoute('enrollment', "{$course->enrollment} of {$course->enrollmentmax}", array($course->dept->shortname,$course->number))}}</td>
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
{{$course->room->building->name}} {{HTML::linkAction('RoomsController@show',"{$course->room->number}", $course->room->id)}}
</td>

<td>
@foreach($course->times AS $time)
{{$time->singleletter}}
@endforeach
@if ($course->times->count() >0)
: {{HTML::linkAction('TimesController@show', "{$course->times[0]->beginning}-{$course->times[0]->end}", $course->times[0]->id)}}
@endif
</td>

<td>
{{$course->prereqs}}
</td>

@endforeach
</tr>
</table>
@stop




