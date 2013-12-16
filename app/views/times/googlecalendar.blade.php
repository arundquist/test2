BEGIN:VCALENDAR<br/>
@foreach ($courses AS $course)
<br/>
@foreach($course->times AS $time)
<?php $var=strtoupper($time->day);
eval("\$dt= Carbon\Carbon::now()->next(Carbon\Carbon::$var);");
$newdt=Carbon\Carbon::createFromFormat('Y-m-j h:ia', "{$dt->toDateString()} {$time->beginning}");
$newdt2=Carbon\Carbon::createFromFormat('Y-m-j h:ia', "{$dt->toDateString()} {$time->end}");
?>
BEGIN:VEVENT<br/>
DTSTART:{{$newdt->format('Ymd\THis') }}<br/>
DTEND:{{$newdt2->format('Ymd\THis') }}<br/>
DESCRIPTION:<br/>
LOCATION:{{$course->room->building->name}} {{$course->room->number}}<br/>
SUMMARY:{{$course->title}}<br/>
END:VEVENT<br/>
<br/>


@endforeach
@endforeach
END:VCALENDAR
