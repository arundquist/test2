@extends('layouts.main')
@section('head')

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          @foreach ($array AS $key=>$value)
          	["{{$key}}", {{$value}}],
          @endforeach
        ]);

        var options = {
          title: '{{$title}}',
          is3D: false,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
    </script>

@stop
@section('main')
<div id="piechart_3d" style="width: 900px; height: 500px;"></div>

@stop
