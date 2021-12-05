<?php

namespace vinepress\Models;

class Users extends Model{

    public function getUserByUserLogin($user_login){
        return get_user_by('login',$user_login);
    }
}