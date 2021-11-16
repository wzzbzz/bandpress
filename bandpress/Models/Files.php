<?php

namespace bandpress\Models;

class Files extends Model{
    
    // check md5's of uploads to see if file exists
    public function fileExists( $filepath ){
        
        $md5 = md5_file($filepath);
        $sql = "SELECT post_id from wp_postmeta WHERE meta_key='md5' AND meta_value='$md5'";
        $results = $this->get_results($sql);
        if(empty($results))
            return false;
        else
            return $results[0];
       
    }

    // all files uploaded by the current user.
    public function getUserFilesSortedByType( $user ){
        $sql = "SELECT * from wp_posts WHERE post_author='{$user->id()}' AND post_type='attachment' ORDER BY post_date DESC";
        $results = $this->get_results($sql);
        $files = [];
        foreach($results as $result){
            $file = new File($result);
            $files[$file->mediaType()][]=$file;
        }
        return $files;
    }


}