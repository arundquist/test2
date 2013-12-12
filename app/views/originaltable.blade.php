<table border=1><tr><th>crn</th><th>course</th><th>title</th><th>instructor</th><th>day</th><th>time</th><th>room</th><th>enrollment</th></tr>
@foreach ($full AS $course) 
	
<tr><td><a href='{{$course["link"]}}'>{{$course["crn"]}}</a></td>
<td>{{$course["title"]}}</td>
<td>{{$course["wordtitle"]}}</td>
<td>{{$course["instructors"]}}</td>
<td>{{$course["days"]}}</td>
<td>{{$course["time"]}}</td>
<td>{{$course["room"]}}</td>
<td></td></tr>

@endforeach
</table>
