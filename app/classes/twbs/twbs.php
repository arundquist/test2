<?php namespace twbs;

class twbs {

    public static function helloWorld()
    {
        return 'does this work?';
    }
    
    public static function dropdown($label)
    {
    	    echo <<<END
    	    <div class="dropdown">
  <button class="btn dropdown-toggle sr-only" type="button" id="dropdownMenu1" data-toggle="dropdown">
    $label
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Action</a></li>
    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another action</a></li>
    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Something else here</a></li>
    <li role="presentation" class="divider"></li>
    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
  </ul>
</div>
END;
    }
    
    public static function dropdownhead($label)
    {
    	    echo <<<END
    	    <div class="dropdown">
  <button class="btn dropdown-toggle sr-only" type="button" id="dropdownMenu1" data-toggle="dropdown">
    $label
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
END;
    }
    
    public static function dropdownlink($text, $url)
    {
    	  echo "<li role='presentation'><a role='menuitem' tabindex='-1' href='$url'>$text</a></li>";
    }
    
    public static function dropdownfoot()
    {
    	    echo "  </ul></div>";
    }
    	    
}
