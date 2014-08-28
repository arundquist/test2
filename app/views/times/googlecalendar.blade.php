BEGIN:VCALENDAR

X-WR-CALNAME:{{$title}}

@foreach ($courses AS $course)
@if(count($course->times)>0)
<?php $days=array();?>
@foreach($course->times AS $time)
<?php 
$days[]=substr(strtoupper($time->day),0,2);?>
@endforeach
<?php
$startdate=Carbon\Carbon::createFromFormat('Ymj h:ia', "{$term->startdate} {$course->times[0]->beginning}");
$enddate=Carbon\Carbon::createFromFormat('Ymj h:ia', "{$term->startdate} {$course->times[0]->end}");
$endrepeatdate=Carbon\Carbon::createFromFormat('Ymj', "{$term->enddate}");
?>
BEGIN:VEVENT
DTSTART;TZID=America/Chicago:{{$startdate->format('Ymd\THis') }}

DTEND;TZID=America/Chicago:{{$enddate->format('Ymd\THis') }}

RRULE:FREQ=WEEKLY;UNTIL={{$endrepeatdate->format('Ymd\THis')}}Z;BYDAY={{implode(",",$days)}}

DESCRIPTION:

SUMMARY:{{$course->title}}

END:VEVENT
@endif
@endforeach
END:VCALENDAR
