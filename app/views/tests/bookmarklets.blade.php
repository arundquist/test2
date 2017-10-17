@extends('layouts.main')

@section('main')
<h1>Drag the links to your bookmarks bar</h1>
<ul class="list-group">
  <li class="list-group-item">
    <a href='javascript:(function(){
document.getElementById("StartDate").value = "01/31/2018";
document.getElementById("StartDate_iso").value="2018-01-31";
document.querySelector(&#39;[title="Start Date"]&#39;).innerHTML="Wednesday January 31, 2018";
document.getElementById("EndDate").value = "05/11/2018";
document.getElementById("EndDate_iso").value="2018-05-11";
document.querySelector(&#39;[title="End Date"]&#39;).innerHTML="Friday May 11, 2018";
})();
'>MWF S18</a> for standard MWF classes in spring 2018 on <a href='https://www.hamline.edu/Content.aspx?ekfrm=4294977374'>this new course form</a>
  </li>
  <li class="list-group-item">
    <a href='javascript:(function(){
document.getElementById("StartDate").value = "02/01/2018";
document.getElementById("StartDate_iso").value="2018-02-01";
document.querySelector(&#39;[title="Start Date"]&#39;).innerHTML="Thursday February 1, 2018";
document.getElementById("EndDate").value = "05/10/2018";
document.getElementById("EndDate_iso").value="2018-05-10";
document.querySelector(&#39;[title="End Date"]&#39;).innerHTML="Thursday May 10, 2018";
})();
'>TR S18</a> for standard TR classes in spring 2018 on <a href='https://www.hamline.edu/Content.aspx?ekfrm=4294977374'>this new course form</a>
  </li>
</ul>
@stop
