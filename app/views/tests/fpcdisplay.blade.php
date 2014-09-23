@extends('layouts.main')
@section('main')
<h1>Evaluations for {{$fac->name}}</h2>
<p>Note that every link just jumps down to the score breakdown and comments for that 
question. The class name is linked to the "overall" questions for that course</p>
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
				<td><a href="#{{$c_id}}">{{$evals['name']}}</a></td>
				@foreach ($evals['avgs'] AS $key=>$avg)
					<td><a href="#{{$c_id}}-{{$key}}">{{$avg}}</a></td>
				@endforeach
			</tr>
		@endforeach
	</tbody>
</table>
</div>

<div>
<h1>Questions</h1>
@foreach ($all AS $c_id=>$evals)
	<div><h2>{{$evals['term']}} {{$evals['name']}}</h2>
	@foreach ($questions AS $key=>$question)
		<div>
			<p><a name="{{$c_id}}-{{$key}}">{{$question}}</a>
			{{$evals['avgs'][$key]}}
			<table class="table-bordered">
			<tr>
				<th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th>
			</tr>
			<tr>
			@foreach ($evals['scores'][$key] AS $score)
				<td>{{$score}}</td>
			@endforeach
			</tr>
			</table>
			</p>
			<ul class="list-group">
				@foreach ($evals['comments'][$key] AS $comment)
					<li class="list-group-item">
						{{$comment}}
					</li>
				@endforeach
			</ul>
		</div>
	@endforeach
	<div>
	<p><a name="{{$c_id}}">Overall</a></p>
	<ul class="list-group">
	@foreach ($evals['overallcomments'] AS $comment)
		<li class="list-group-item">
			{{$comment}}
		</li>
	@endforeach
	</ul>
@endforeach
</div>
	


@stop