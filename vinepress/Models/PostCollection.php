<?php

namespace vinepress\Models;

class PostCollection extends ModelCollection{

    public function __construct(){
        parent::__construct();
    }

    public function byId( $id ){
        $post = get_post ( $id );
        $class = $this->class;
        return new $class( $post );
    }

}