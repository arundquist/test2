<h1>{{$time->day}}: {{$time->beginning}}-{{$time->end}}</h1>
@foreach ($courses AS $course)
{{$course->dept->shortname}} {{$course->number}} {{$course->section}} {{$course->term_id}}<br/>
@endforeach