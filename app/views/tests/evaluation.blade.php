@extends('layouts.main')
@section('main')
<h1>Evaluations for {{$course->title}} {{$course->term->season}} {{$course->term->ay}}</h1>
<h2>Instructor: {{$course->instructors->first()->name}}</h2>
<table class="table table-striped">
<thead>
	<tr>
		<th>Question</th>
		<?php
			for ($i=1; $i<=7; $i++)
			{
				echo "<th>$i</th>";
			};
		?>
		<th>Average</th>
	</tr>
</thead>
<tbody>
	@foreach($betterarray AS $key => $row)
		<tr>	
			<td>
				{{$questions[$key]}}
			</td>
			@foreach($row AS $value)
				<td>
					{{$value}}
				</td>
			@endforeach
			<td>
				{{$avgs[$key]}}
			</td>
		</tr>
	@endforeach
</tbody>
</table>


@stop