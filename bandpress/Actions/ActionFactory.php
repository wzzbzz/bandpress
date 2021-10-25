<?php

namespace bandpress\Actions;

class ActionFactory{
    public function __construct(){}
    public function fromQueryVar(){
        $action = get_query_var("action");
        $classname = "\\bandpress\\Actions\\".ucfirst($action)."Action";
        return new $classname();
    }
}