@extends('layouts.main')

@section('main')
<h1>Report for {{$dept->shortname}}</h1>
<table class="table-bordered">
<thead>
	<th></th>
	@foreach (array_keys($whole) AS $ay)
		<th>{{$ay}}</th>
	@endforeach
</thead>
@foreach ($keys AS $key)
	<tr>
		<td>{{$key}}</td>
		@foreach (array_column($whole, $key) AS $row)
			<td>{{$row}}</td>
		@endforeach
	</tr>
@endforeach
</table>

<div>
The rows should be relatively self-explanatory. Things like "less20" mean
classes with caps less than 20, for example. For the faculty, the numbers next 
to each name [a(b)-c(d)] are (a) the number of sections taught in the department,
(b) the number
of credits those sections offered divided by 4, (c) and (d) are the same
for courses outside of the department. Note that FSEM is considered outside
for this calculation.
</div>


@stop