@extends('layouts.main')

@section('main')
<table class='table table-bordered'>
    <tr>
      <th>Time</th>
      <th>Number of courses</th>
      <th>total enrollment</th>
    </tr>
@foreach ($times AS $time)
  <tr>
    <td>{{$time->ft}}</td>
    <td>{{$time->course_count}}</td>
    <td>{{$time->totalenrollment}}</td>
  </tr>
@endforeach
</table>
@stop
