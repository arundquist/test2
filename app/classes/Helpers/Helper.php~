<?php namespace Helpers;

class Helper {

    public static function helloWorld()
    {
        return 'Hello World';
    }
    
    public static function courselist($model, $id)
    {
    	    $mod=$model::findOrFail($id);
    	    $cs=$mod->courses()->where("term_id",'=',\Session::get('term_id'))
    	    	->where("cancelled",0)->get();
    	    $cs->load('instructors', 'hps','room.building','dept','times', 'areas','term');
    	    return $cs;
    }
    
    public static function courselistwithmodel($mod)
    {
    	    $cs=$mod->courses()->where("term_id",'=',\Session::get('term_id'))
    	    	->where("cancelled",0)->get();
    	    $cs->load('instructors', 'hps','room.building','dept','times', 'areas','term');
    	    return $cs;
    }
		
}
