<?php

namespace bandpress\Actions;

class AddBandAction{
    private $bandName;
    private $isCurrentUsersBand;
    public function __construct(){
        $this->bandName = $_REQUEST['bandName'];
        $this->isCurrentUsersBand = $_REQUEST['isMyBand'];
    }
    public function __destruct(){}
    public function do(){

        $band = $this->bandExists();
        if(!$band){

            #add new band taxonomy;
            #get band object, add current user.
            $term = wp_insert_term($this->bandName,'bands');
            wp_redirect("/band/{$id}");

        }
        else{
            # print error message that band exists
            wp_redirect("/band/{$id}");
        }

        die;
    }

    private function bandExists(){
        
        $term = get_term_by('name',addslashes($this->bandName),'bands');
        return $term!==false;
       
    }
}