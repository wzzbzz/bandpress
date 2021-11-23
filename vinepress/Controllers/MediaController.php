<?php

namespace vinepress\Controllers;

class MediaController{
    public function __cosntruct(){}
    public function __destruct(){}
    public function init(){
        self::addRoutes();
    }
    public function addRoutes(){
        add_rewrite_rule("^actions/upload/?$", "index.php?action=upload", "top");
    }
}