<?php

namespace bandpress\Actions;

class AddBandMemberAction{
    private $bandId;
    private $newUserName;
    public function __construct(){
        $this->bandId = $_REQUEST['bandId'];
        $this->newUserName = $_REQUEST['bandMemberName'];
    }
    public function __destruct(){}
    public function do(){
        $term = get_term($this->bandId, "band");
        $users = new \bandpress\Models\Users();
        diebug($users->getUserByDisplayName($this->newUserName));
        
    }

    private function bandExists(){
        return true;
        $term = get_term($this->bandId,'band');

        # here for testing purposes
        if($term){
            wp_delete_term($term->term_id,'band');
        }
        return false;
        return $term!==false;
       
    }
}