@extends('layouts.main')
@section('main')
<h1>Evaluations for {{$fac->name}}</h2>
<div class='row'>
<div>
<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Term</th>
			<th>Course</th>
			@foreach ($questions AS $question)
				<th>{{$question}}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@foreach ($all AS $c_id=>$evals)
			<tr>
				<td>{{$evals['term']}}</td>
				<td>{{$evals['name']}}</td>
				@foreach ($evals['avgs'] AS $avg)
					<td>{{$avg}}</td>
				@endforeach
			</tr>
		@endforeach
	</tbody>
</table>
</div>


@stop