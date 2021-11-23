<?php

namespace vinepress\Views\ComponentViews;

class AudioView{
    private $data;
    public function __construct( $data ){
        $this->data = $data;
    }
    public function render(){
        ?>
        <div class="mb-2 d-flex justify-content-center">
        <audio controls>
            <source src="<?=$this->data->resource_url();?>" type="<?=$this->data->mime_type();?>">
            Your browser does not support the audio element.
        </audio>
        </div>
        <?php
    }
}