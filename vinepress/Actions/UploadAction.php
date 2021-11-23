<?php

namespace vinepress\Actions;

use \vinepress\Models\Files;

/* wp admin api's for uploads */
require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');

class UploadAction{
    public function __construct(){}
    public function __destruct(){}
    public function do(){

        if($id=$this->handleFileUpload()){
            wp_redirect("/files/{$id}");
        }
        
        else{
            wp_redirect("/");
        }

        die;
    }

    protected function handleFileUpload(){
        if(($id = $this->uploadIsValid())===true){
            
            $id = media_handle_upload('file',0);
    
            $post = get_post($id);
            $file = new \vinepress\Models\File($post);

            // hook this into the attachment post_save process 
            $file->setMd5();
            $id = $file->id();
            $_SESSION['message']="File Upload Successful.";

            return $id;
        }
        return $id;
    }
    private function uploadIsValid(){

        // files model queries for files
        $files = new \vinepress\Models\Files();
        // check for md5 to see if file exists
        if($id = $files->fileExists($_FILES['file']['tmp_name'])){
            $_SESSION['message']="This file has already been uploa
            ded.";
            return $id->post_id;
        }
        
        return true;
        
    }
}