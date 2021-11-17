<?php

namespace bandpress\Views\ComponentViews;

class VideoView{
    private $data;
    public function __construct( $data ){
        $this->data = $data;
    }
    public function render(){
        ?>
        <div class="container">
        <video controls width="100%">
        <source src="<?=$this->data->resource_url();?>" type="<?=$this->data->mime_type();?>">
        Your browser does not support the audio element.
        </video>
        </div>
        <?php
    }
}