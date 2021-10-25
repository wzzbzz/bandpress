<?php

namespace bandpress\Views\ComponentViews;

class AudioView{
    private $data;
    public function __construct( $data ){
        $this->data = $data;
    }
    public function render(){
        ?>
        <div class="mb-md-5 mt-md-4 pb-5 d-flex justify-content-center"><?=$this->data->title();?></div>
        <div class="mb-md-5 mt-md-4 pb-5 d-flex justify-content-center">
        <audio controls>
        <source src="<?=$this->data->resource_url();?>" type="<?=$this->data->mime_type();?>">
        Your browser does not support the audio element.
        </audio>
        </div>
        <?php
    }
}