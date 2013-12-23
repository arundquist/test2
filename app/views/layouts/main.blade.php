<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
		<style>
			table form { margin-bottom: 0; }
			form ul { margin-left: 0; list-style: none; }
			.error { color: red; font-style: italic; }
			body { padding-top: 20px; }
		</style>
	</head>

	<body>
	
		<div class="container">
			@if (Session::has('message'))
				<div class="flash alert">
					<p>{{ Session::get('message') }}</p>
				</div>
			@endif
			<!-- Static navbar -->
      <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">SS LF BD dB</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active">{{HTML::linkAction('DeptsController@index', "Departments")}}</li>
            <li>{{HTML::linkAction('InstructorsController@index', "Instructors")}}</li>
            <li>{{HTML::linkAction('BuildingsController@index', "Buildings")}}</li>
            <li>{{HTML::linkAction('TimesController@index', "Times")}}</li>
            <li>{{HTML::linkAction('AreasController@index', "Areas of Study")}}</li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">HP's <b class="caret"></b></a>
              <ul class="dropdown-menu">
              @foreach ($hps AS $hp)
                <li>{{HTML::linkAction('HpsController@show',"{$hp->letter}", $hp->id)}}</li>
              @endforeach
              </ul>
            </li>
          </ul>
           <ul class="nav navbar-nav navbar-right">
           @yield('navcomplete')
          </ul>
         
        </div><!--/.nav-collapse -->
      </div>
			
			@yield('main')
			
		</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
	</body>

</html>