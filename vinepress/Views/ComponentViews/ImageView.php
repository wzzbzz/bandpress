<?php

namespace vinepress\Views\ComponentViews;

class ImageView{

    private $data;

    public function __construct( $data ){
        $this->data = $data;
    }

    public function render(){
        ?>
         <div class="mb-2 d-flex justify-content-center">
            <img class="img-fluid" src="<?= $this->data->resource_url();?>" alt="<?= $this->data->title();?>" class="img-fluid"/>
         </div>
        <?php
    }
}