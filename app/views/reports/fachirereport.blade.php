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


@stop