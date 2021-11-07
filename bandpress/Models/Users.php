<?php

namespace bandpress\Models;

class Users extends Model{

    public function getUserByDisplayName($display_name){
        $sql = "SELECT * from wp_users WHERE display_name='{$display_name}'";
        if(!empty($results = $this->get_results($sql))){
            foreach($results as $result){
                diebug($result);
            }
        }

    }
}