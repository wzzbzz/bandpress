<?php

namespace vinepress\Models;

class Posts extends Model{

    public function getPostByTitle($title, $post_type="post"){
        return get_page_by_title($title, OBJECT, $post_type);
    }

}