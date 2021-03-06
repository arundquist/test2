BEGIN:VCALENDAR

X-WR-CALNAME:{{$title}}

@foreach ($courses AS $course)
@if(count($course->times)>0)
<?php $days=array();?>
@foreach($course->times AS $time)
<?php 
$day=substr(strtoupper($time->day),0,2);?>

<?php
$startdate=Carbon\Carbon::createFromFormat('Ymj h:ia', "{$term->startdate} {$time->beginning}");
$enddate=Carbon\Carbon::createFromFormat('Ymj h:ia', "{$term->startdate} {$time->end}");
$endrepeatdate=Carbon\Carbon::createFromFormat('Ymj', "{$term->enddate}");
?>
BEGIN:VEVENT
DTSTART;TZID=America/Chicago:{{$startdate->format('Ymd\THis') }}

DTEND;TZID=America/Chicago:{{$enddate->format('Ymd\THis') }}

RRULE:FREQ=WEEKLY;UNTIL={{$endrepeatdate->format('Ymd\THis')}}Z;BYDAY={{$day}}

DESCRIPTION:

LOCATION:@foreach ($course->rooms AS $room){{$room->building->name}} {{$room->number}}, @endforeach

SUMMARY:{{$course->title}}

END:VEVENT
@endforeach
@endif

@endforeach
END:VCALENDAR
