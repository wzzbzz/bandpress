<?php

namespace bandpress\Controllers;

class SessionController{
    public function __construct(){}

    // to be called in the WP 'init' hook
    public function init(){

        session_start();
        self::addRoutes();
        
    }

    private function addRoutes(){
        // add login action
        add_rewrite_rule("^actions/registerUser/?$", "index.php?action=registerUser", "top");
        add_rewrite_rule("^actions/login/?$", "index.php?action=login", "top");
        add_rewrite_rule("^actions/logout/?$", "index.php?action=logout", "top");
        // add logout action

    }
}