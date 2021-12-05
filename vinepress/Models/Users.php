<?php

namespace vinepress\Models;

class Users extends Model{

    public function getUserByUserLogin($user_login){
        $sql = "SELECT ID from wp_users WHERE user_login='{$user_login}' LIMIT 1";
        if(!empty($results = $this->get_results($sql))){
            $wpuser = new \WP_User($results[0]);
            return new User($wpuser);
        }

        else return false;

    }
}