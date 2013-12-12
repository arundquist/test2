<p>{{$room->building->name}} {{$room->number}}</p>
<ul>
@foreach ($room->courses AS $course)
<li>{{$course->dept->shortname}} {{$course->number}}</li>
@endforeach
</ul>