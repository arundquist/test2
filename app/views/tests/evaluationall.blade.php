@extends('layouts.main')
@section('main')
<h1>Evaluations for {{$course->term->season}} {{$course->term->ay}}</h1>
<h2>Instructor: {{$course->instructors->first()->name}}</h2>
<div class='row'>
<div>
<table class="table table-striped table-bordered">
<thead>
<tr>
	<th>Question</th>
	@foreach ($betterarray AS $crn=>$ba)
		<th>{{$classnames[$crn]}} {{$completeinfo[$crn]}}</th>
	@endforeach
</tr>
</thead>
<tbody>
	@foreach ($questions AS $qnum=>$question)
		<tr>
			<td>{{$question}}</td>
			@foreach ($classnames AS $crn=>$title)
				<td>{{$avgs[$crn][$qnum]}}</td>
			@endforeach
		</tr>
	@endforeach
</tbody>
</table>

</div>
<div>
@foreach ($betterarray AS $crn=>$betterarray)
	
	
	<table class="table-striped table-bordered">
	<caption>{{$classnames[$crn]}}</caption>
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
					{{$avgs[$crn][$key]}}
				</td>
			</tr>
		@endforeach
	</tbody>
	</table>
	
@endforeach
</div>
<div>
<ul class='list-group'>
	@foreach ($comments AS $crn=>$commentlist)
		<li>
			{{$classnames[$crn]}}
		</li>
		@foreach ($commentlist AS $comment)
			<li class='list-group-item'>
				{{$comment}}
			</li>
		@endforeach
	@endforeach
</ul>
</div>
</div>


@stop