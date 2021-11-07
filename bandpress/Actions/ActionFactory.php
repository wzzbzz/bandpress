<?php

namespace bandpress\Actions;

class ActionFactory{
    private $package;
    public function __construct($package){
        if(empty($package)){
            $package = "bandpress";
        }
        $this->package = $package;
    }
    public function fromQueryVar(){
        $action = get_query_var("action");
        $classname = "\\{$this->package}\\Actions\\".ucfirst($action)."Action";
        return new $classname();
    }
}