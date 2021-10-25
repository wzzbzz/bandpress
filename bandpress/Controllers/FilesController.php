<?php

namespace bandpress\Controllers;

class FilesController{
    public function __construct(){}
    public function __destruct(){}
    public function init(){
        self::addRoutes();
        add_action('post_save',array(self::class,"save"));
    }
    public function save($data){
        return;
    }
    public function addRoutes(){
        // file listing page
        add_rewrite_rule("^files/?$", "index.php?pagename=files", "top");
        // single file display page
        add_rewrite_rule("^files/([^\/]+)/?$", "index.php?pagename=file&post_id=\$matches[1]", "top");
    }
}