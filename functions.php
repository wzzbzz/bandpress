<?php

$appname="bandpress";

//autoload function
spl_autoload_register(
    function($classname) {
        global $appname;
        if(strpos($classname, $appname) ===false || strpos($classname, $appname ) > 0  ){
			return;
		}

		$include = get_template_directory() . "/" . str_replace( "\\","/",$classname).".php";
        
		if(file_exists($include))
			include_once($include);
		else{
            debug($include);
            debug($classname);
            diebug(debug_backtrace());
			
		}
    }
);

//debugging functions
if(!function_exists('debug')) {

    function debug($obj, $suppress = false)
    {

        echo '<pre><font size=2>';
        var_dump($obj);
        echo '</font></pre>';

        if(!$suppress) {
            $trace = debug_backtrace();
            echo "<font size=2>" . $trace[0]['file'];
            echo ':' . $trace[0]['line'] . '</font>';
        }

    }
}

if(!function_exists('diebug')) {

    function diebug($obj, $suppress = false)
    {

        echo '<pre><font size=2>';
        var_dump($obj);
        echo '</font></pre>';

        if(!$suppress) {
            $trace = debug_backtrace();
            echo "<font size=2>" . $trace[0]['file'];
            echo ': ' . $trace[0]['line'] . '</font>';
        }

        die();
    }
}
flush_rewrite_rules();
$app = new \bandpress\Controllers\ApplicationController();

function app(){
    global $app;
    return $app;
}

function sys(){
    return new \bandpress\Systems\System();
}