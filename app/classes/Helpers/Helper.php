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
    	    $cs->load('instructors', 'hps','rooms.building','dept','times', 'areas','term');
    	    return $cs;
    }
    
    public static function courselistwithmodel($mod)
    {
    	    $cs=$mod->courses()->where("term_id",'=',\Session::get('term_id'))
    	    	->where("cancelled",0)->get();
    	    $cs->load('instructors', 'hps','rooms.building','dept','times', 'areas','term');
    	    return $cs;
    }
    
    public static function spark($vals)
	{
		
		//$vals=[4,5,32,2,0,6,7];
		$max=max($vals);
		$h=25;
		$w=35;
		$d=$w/7;
		$ret= "<svg width='$w' height='$h'>";
		$ret.= "<polyline points=\"0,$h ";
		foreach ($vals AS $key=>$val)
		{
			$x=$key/7*$w;
			$y=($max-$val)*$h/$max;
			$val=$val*10;
			$x2=$x+$d;
			$ret.= "$x,$y $x2,$y ";
		};
		$ret.= $w.','.$h.'" style="fill:red;stroke:red;stroke-width:1" /></svg>';
		echo $ret;
    	}
		
}
