<?php

namespace bandpress\Views\ComponentViews;

class ImageView{

    private $data;

    public function __construct( $data ){
        $this->data = $data;
    }

    public function render(){
        ?>
         <div class="mb-md-5 mt-md-4 pb-5 d-flex justify-content-center">
         <img src="<?= $this->data->resource_url();?>" alt="<?= $this->data->title();?>" class="img-fluid"/>
         </div>
        <?php
    }
}