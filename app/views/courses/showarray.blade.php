@extends('layouts.main')

@section('main')

<table class="table-striped table-bordered">
<thead>
<tr>
@foreach(array_keys($all[0]) AS $key)
<th>{{$key}}</th>
@endforeach
</tr>
</thead>
@foreach($all AS $course)
<tr>
@foreach($course AS $thing)
<td>{{$thing}}</td>
@endforeach
</tr>
@endforeach
</table>

@stop
