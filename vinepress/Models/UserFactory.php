<?php

/*
 *  class \vinepress\Models\UserFactory
 *
 *  for making User objects from slug or ID
 *  
 */

 
 namespace vinepress\Models;

 class UserFactory{
    
    public function __construct(){}
    public function __destruct(){}
    
    public function fromSlug( $slug ){
        $user = get_user_by( 'slug' , $slug );
        return new User($user);
    }
    
    public function fromID( $id ){
        $user = get_user_by( 'ID' , $id );
        return new User( $user );
    }
    
    public function fromName( $name ){
        global $wpdb;
        $sql = "SELECT ID from wp_users WHERE display_name='$name'";
        $results = $wpdb->get_results($sql);
        if($results){
            return self::fromID( $results[0]->ID);   
        }
        else
            return false;
    }
    
    public function fromObject( $user ){
        return new User( $user );
    }
    
 }