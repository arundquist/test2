@foreach ($depts AS $dept)
{{HTML::linkAction('DeptsController@show', "$dept->shortname ({$dept->courses->count()})", array($dept->id))}} 

<br/>
@endforeach


