<?php

namespace bandpress\Systems;


/* System methods made available globally via an API*/
class System{
    public function __construct(){}
    public function __destruct(){}
    public function currentUser(){
        if( is_user_logged_in() ){
            return new \bandpress\Models\User(wp_get_current_user());
        }
        return false;
    }
}