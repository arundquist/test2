@extends('layouts.main')

@section('main')
@if ($count >0)

<table class="table">
<thead>
	<tr>
		<th>term</th>
		<th>course</th>
		
		<th>overall average</th>
		@foreach (array_column($everything[0]["evals"][$questionkey]["allquestions"],"question") AS $q)
			<th>{{$q}}</th>
		@endforeach
	</tr>
	
</thead>
<tbody>
	@foreach ($everything AS $tkey=>$term)
		
		@foreach ($term["evals"] AS $ckey=>$course)
			<tr>
				<td>{{Helper::fixtermstring($term["term"])}}</td>
				<td><a href="#{{$tkey}}-{{$ckey}}">{{$course["title"]}}</a></td>
				<td>{{$course["overallavg"]}}</td>
				@foreach ($course["allquestions"] AS $qkey=>$question)
					<td>{{Helper::sparkflex(array_column($question["details"], "votes"))}}
					<br/><span title="{{$question['question']}}"><a href="#{{$tkey}}-{{$ckey}}-{{$qkey}}">{{$question['average']}}</a></span></td>
					</td>
				@endforeach
			</tr>
		
		@endforeach
	@endforeach
</tbody>
</table>

@foreach ($everything AS $tkey=>$term)
	@foreach ($term["evals"] AS $ckey=>$course)
		<h2><a name="{{$tkey}}-{{$ckey}}"></a>{{Helper::fixtermstring($term["term"])}}: {{$course["title"]}}</h2>
		@foreach ($course["allquestions"] AS $qkey=>$question)
			<a name="{{$tkey}}-{{$ckey}}-{{$qkey}}"></a>
			<p>{{$question['question']}}</p>
			<ul class="list-group">
			@foreach ($question['comments'] AS $comment)
				<li class="list-group-item">
					{{$comment}}
				</li>
			@endforeach
			</ul>
		@endforeach
		<p>Other questions</p>
		@foreach ($course["justquestions"] AS $qkey=>$question)
			<p>{{$question['question']}}</p>
			<ul class="list-group">
			@foreach ($question['comments'] AS $comment)
				<li class="list-group-item">
					{{$comment}}
				</li>
			@endforeach
			</ul>
		@endforeach
		<p>Overall comments:</p>
		<ul class=class"list-group">
		@foreach ($course["overallcomments"] AS $comment)
			<li class="list-group-item">
				{{$comment}}
			</li>
		@endforeach
		</ul>
	@endforeach
	
@endforeach
		
			

@endif



@stop