@extends('layouts.main')

@section('main')
<ul class="list-inline">
@foreach ($areas AS $area)
<li>{{HTML::linkAction('AreasController@show', "{$area->area}", $area->id)}}</li>
@endforeach
</ul>
@stop