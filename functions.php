<?php

$appname="bandpress";

function preInitialize($appname){

    // get namespace directory list option
    
    if( !is_array( get_option( 'root_paths' ) ) ){
        // add template directory to the list
        $namespaces = array(get_template_directory());
        update_option('root_paths', array($appname=>get_template_directory()));
        
    }

}

preInitialize( $appname );

//autoload function
spl_autoload_register(
    function($classname) {
        
        // check if this class is in the family
        $root_paths = get_option("root_paths");
    //    $found = false;
        foreach($root_paths as $appname=>$path){
  //          debug($appname);
            $found = $found || ( strpos($classname, $appname) !== false && strpos($classname, $appname ) == 0  );
        }
//diebug($found);

        // not one of ours, get out.
        if(!$found)
            return;

        // what's the better way than $found flag stuff
        $found = false;
        foreach($root_paths as $appname=>$path){

		    $include = $path . "/" . str_replace( "\\","/",$classname).".php";

		    if(file_exists($include)){
			    include_once($include);
                $found = true;
            }
		    
        }

        if(!$found){
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