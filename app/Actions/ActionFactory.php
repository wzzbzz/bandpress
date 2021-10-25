<?php

namespace app\Actions;

class ActionFactory{
    public function __construct(){}
    public function fromQueryVar(){
        $action = get_query_var("action");
        $classname = "\\app\\Actions\\".ucfirst($action)."Action";
        return new $classname();
    }
}