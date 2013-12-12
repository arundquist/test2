<table>
<tr>
<th>crn</th>
<th>Dept</th>
<th>Number</th>
<th>section</th>
<th>title</th>
<th>Hamline Plan</th>
<th>instructors</th>
<th>days and times</th>
<th>where</th>
</tr>
@foreach($biglist AS $course)
<tr>
<td>{{$course["crn"]}}</td>
<td>{{$course["dept"]}}</td>
<td>{{$course["num"]}}</td>
<td>{{$course["sec"]}}</td>
<td>{{$course["title"]}}</td>
<td>
@foreach($course["hps"] AS $hp)
{{$hp}},
@endforeach
</td>
<td>
@foreach($course["instructors"] AS $instructor)
{{$instructor}}<br/>
@endforeach
</td>
<td>
	@foreach($course["days"] AS $day)
	{{$day}}, 
	@endforeach
	{{$course["starttime"]}}-{{$course["endtime"]}}
</td>
<td>
{{$course["building"]}} {{$course["room"]}}
</td>
@endforeach
</tr>
</table>
