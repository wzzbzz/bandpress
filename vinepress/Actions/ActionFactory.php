<?php

namespace vinepress\Actions;

class ActionFactory{
    private $package;
    public function __construct($package="vinepress"){
        if(empty($package)){
            $package="vinepress";
        }
        $this->package = $package;
    }
    public function fromQueryVar(){
        $action = get_query_var("action");

        $classname = "\\{$this->package}\\Actions\\".ucfirst($action)."Action";
        return new $classname();
    }
}