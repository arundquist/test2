@extends('layouts.main')
@section('main')
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
        	['Year', 'Enrollment', 'Cap'],
        @foreach ($courses AS $course)
        ['{{$course->term->ay}} {{$course->term->season}}-{{$course->section}}', {{$course->enrollment}}, {{$course->enrollmentmax}}],
        @endforeach
        ]);

        var options = {
        	title: '{{$dept->shortname}} {{$courses[0]->number}} enrollment'
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>

  <h1>{{$courses[0]->title}}</h1>
    <div id="chart_div" style="width: 900px; height: 500px;"></div>


    {{$courses[0]->description}}

    <div>
      <table class='table'>
        <tr>
          <th>Term</th>
          <th>enrollment</th>
          <th>cap</th>
        </tr>
        @foreach ($courses as $course)
          <tr>
            <td>{{$course->term->ay}} {{$course->term->season}} {{$course->section}}</td>
            <td>{{$course->enrollment}}</td>
            <td>{{$course->enrollmentmax}}</td>
          </tr>
        @endforeach
      </table>
    </div>
@stop
