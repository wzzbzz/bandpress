<?php

namespace vinepress\Models;

class Users extends Model{

    public function getUserByDisplayName($display_name){
        $sql = "SELECT ID from wp_users WHERE display_name='{$display_name}' LIMIT 1";
        if(!empty($results = $this->get_results($sql))){
            $wpuser = new \WP_User($results[0]);
            return new User($wpuser);
        }

        else return false;

    }
}