BEGIN:VCALENDAR
@foreach ($courses AS $course)

@foreach($course->times AS $time)
<?php $var=strtoupper($time->day);
eval("\$dt= Carbon\Carbon::now()->next(Carbon\Carbon::$var);");
$newdt=Carbon\Carbon::createFromFormat('Y-m-j h:ia', "{$dt->toDateString()} {$time->beginning}");
$newdt2=Carbon\Carbon::createFromFormat('Y-m-j h:ia', "{$dt->toDateString()} {$time->end}");
?>
BEGIN:VEVENT
DTSTART:{{$newdt->format('Ymd\THis') }}

DTEND:{{$newdt2->format('Ymd\THis') }}

DESCRIPTION:

LOCATION:{{$course->room->building->name}} {{$course->room->number}}

SUMMARY:{{$course->title}}

END:VEVENT


@endforeach
@endforeach
END:VCALENDAR
