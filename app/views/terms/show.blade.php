@extends('layouts.main')
@section('main')
<ul class="list-inline">
@foreach ($depts AS $dept)
<li>{{HTML::linkAction('DeptsController@show', "$dept->shortname ({$dept->courses->count()})", array($dept->id))}} 
</li>
@endforeach
</ul>
@stop


