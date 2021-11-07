<?php

namespace bandpress\Models;

class Band extends TaxonomyTerm{

    public function setLeaders(){}

    public function members(){
        $members = [];
        foreach($this->get_field('members') as $member){
            $members[] = new User($member['member']);
        }
        return $members;
    }
    public function addMember($userName){
        
    }
    
}