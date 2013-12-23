@extends('layouts.main')

@section('main')
<table class="table-striped table-bordered">
<thead>
<tr>
<th>Building</th>
<th>Rooms</th>
</tr>
</thead>
@foreach ($buildings AS $building)
<tr>
<td>
{{$building->name}}
</td>
<td>
<ul class="list-inline">
@foreach ($building->rooms AS $room)
<li>{{HTML::linkAction('RoomsController@show',"{$room->number}", $room->id)}}</li>
@endforeach
</ul>
</td>
</tr>
@endforeach
@stop