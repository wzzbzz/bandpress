<?php
namespace vinepress\Models;


class Model{

    public function __construct(){
    }
    public function __destruct(){}

    public function db(){
        global $wpdb;
        return $wpdb;
    }
    public function get_results( $sql ){
        return $this->db()->get_results( $sql );
    }
    
    public function query( $sql ){
        return $this->db()->query( $sql );
    }
    
    public function prepare( $sql, $args ){
        return $this->db()->prepare( $sql, $args );
    }
    
    public function get_field( $field_name , $format_result = true ){
        return get_field( $field_name , $this->acf_id() , $format_result );
    }
    
    public function update_field( $field_name, $value ){

        // helper function for working with acf
        // each WP entity must implement it own version of acf_id();
        update_field( $field_name, $value, $this->acf_id());
    }
    
    public function acf_id(){
        // no base class ACF interaction with
        return false;
    }
}