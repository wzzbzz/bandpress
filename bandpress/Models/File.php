<?php

namespace bandpress\Models;

class File extends Post{

    public function postMimeType(){
        return $this->data->post_mime_type();
    }

    public function isAudio(){

    }

    public function path(){
        return get_attached_file($this->id());
    }

    public function setmd5(){
        $this->update_meta('md5',md5_file($this->path()));
    }

    public function md5(){
        return $this->get_meta('md5',true);
    }

    public function authorName(){
        
    }

    public function meta(){
        return wp_get_attachment_metadata($this->id());
    }

    public function mime_type(){
        return $this->wp_post->post_mime_type;;
    }

    public function mediaType(){
    
        preg_match('/([a-z]+)\/.*$/',$this->mime_type(),$matches);


        return $matches[1];
        
    }

    public function resource_url(){
        return wp_get_attachment_url($this->id());
    }


}