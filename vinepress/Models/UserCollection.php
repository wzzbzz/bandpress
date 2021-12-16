<?php

namespace vinepress\Models;

class UserCollection extends ModelCollection{

    public function __construct(){
        parent::__construct();
    }

    public function byId( $id ){
        $post = get_user_by ( 'ID', $id );
        $class = $this->class;
        return new $class( $post );
    }

}