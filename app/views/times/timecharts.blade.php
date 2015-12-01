@extends('layouts.main')

@section('main')
<h2>{{$title}}</h2>
{{Helper::maketimeplot($MWF)}}
</br>
{{Helper::plotlegend()}}
</br>

{{Helper::maketimeplot($TR)}}
@stop
