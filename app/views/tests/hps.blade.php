@extends('layouts.main')
@section('main')
<h1>
Hamline Plan for {{$dept->shortname}} {{$term->season}} {{$term->ay}}
</h1>
<table class='table-striped table-bordered'>
<thead>
<tr>
	<th>Hamline plan letter</th>
	<th>Courses</th>
</tr>
</thead>
<tbody>
@foreach ($list AS $letter=>$cs)
	<tr>
		<td>{{$letter}}</td>
		<td>
			<ul class = "list-group">
				@foreach ($cs AS $c)
					<li class = "list-group-item">
						{{$c->number}}-{{$c->section}}
						({{$c->enrollment}}/{{$c->enrollmentmax}})
					</li>
				@endforeach
			</ul>
		</td>
	</tr>
@endforeach
</table>

@stop
