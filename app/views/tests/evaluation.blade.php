@extends('layouts.main')
@section('main')
<h1>Evaluations for {{$course->title}} {{$course->term->season}} {{$course->term->ay}}</h1>
<h2>Instructor: {{$course->instructors->first()->name}}</h2>
<div class='row'>
<div class='col-md-4'>
<table class="table table-striped table-bordered">
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
</div>
<div class='col-md-8'>
<ul class='list-group'>
	@foreach ($comments AS $comment)
		<li class='list-group-item'>
			{{$comment}}
		</li>
	@endforeach
</ul>
</div>
</div>


@stop