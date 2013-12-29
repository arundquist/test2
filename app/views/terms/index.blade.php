@extends('layouts.main')
@section('main')
<div class="list-group">
@foreach ($terms AS $term)
{{link_to_action('TermsController@sendback', "$term->ay $term->season", array($term->id),['class'=>'list-group-item'])}}
@endforeach
</div>
@stop