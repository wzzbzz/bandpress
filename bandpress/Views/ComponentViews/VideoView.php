<?php

namespace bandpress\Views\ComponentViews;

class VideoView{
    private $data;
    public function __construct( $data ){
        $this->data = $data;
    }
    public function render(){
        ?>
        <div class="mb-md-5 mt-md-4 pb-5 d-flex justify-content-center"><?=$this->data->title();?></div>
        <div class="container mb-md-5 mt-md-4 pb-5 d-flex justify-content-center">
        <video controls width="50%">
        <source src="<?=$this->data->resource_url();?>" type="<?=$this->data->mime_type();?>">
        Your browser does not support the audio element.
        </video>
        </div>
        <?php
    }
}