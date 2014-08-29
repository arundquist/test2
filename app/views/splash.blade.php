@extends('layouts.main')
@section('main')
<div class="jumbotron">
<h1>
Welcome to the SS, LF, BD, dB!
</h1>
<p>This is the Super Secret, Lightning Fast, Back Door, dataBase for the Hamline
Course schedule. It was created by Andy Rundquist as a fun project to flex
his php scripting muscles. He scraped all the public information from the
piperline course schedule and repackaged it into this. Enjoy!</p>
<p>
{{link_to_action('TermsController@index','choose a term',array(),['class'=>'btn btn-primary'])}}
</p>
<p>
Once you've picked a term, you'll start by choosing a department. Then all
the courses for that department in that term will be displayed. At that point
nearly everything is click-able, so that you can see all courses that share, say,
that instructor, or that room, or that time, or that area of study, or that
Hamline Plan letter. You can also see full lists of those choices using
the menus at the top.
</p>
<p>
Note that you can also see the full history of teaching for a particular
instructor if you go to the "instructors" link in the menu. All instructors
are listed with a "history" link next to each.
</p>
</div>
@stop
