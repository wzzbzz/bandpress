<?php

namespace vinepress\Views\PageViews;

class FileViewFactory{
    public function __construct(){}
    public function __destruct(){}
    public function fromObject( $obj ){
        switch($obj->mediaType()){
            case "audio":
                
                $view = new AudioFileView( $obj );
                return $view;
                break;
            case "image":
                break;
            case "video":
                $view = new VideoFileView( $obj );
                return $view;
                break;
        }
    }
}