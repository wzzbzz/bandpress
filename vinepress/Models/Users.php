<?php

namespace vinepress\Models;

class Users extends Model{

    public function getUserByUserLogin($user_login){

        $user =  get_user_by('login',$user_login);
        if(!is_wp_error($user)){
            return new User($user);
        }
        else{
            return $user;
        }
    }
}