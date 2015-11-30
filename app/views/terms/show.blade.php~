@extends('layouts.main')
@section('main')
<ul class="list-inline">
@foreach ($depts AS $dept)
<li>{{HTML::linkAction('DeptsController@show', "$dept->shortname ({$dept->courses->count()})", array($dept->id))}} 
</li>
@endforeach
</ul>
{{link_to_action('TestsController@getAllclasses', "json data for this term", Session::get('term_id'))}}
@stop


