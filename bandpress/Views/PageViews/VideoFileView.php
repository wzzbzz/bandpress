<?php

namespace bandpress\Views\PageViews;

use bandpress\Views\View;

class VideoFileView extends View{
    protected function renderBody(){

        $this->renderTitle();
        $this->renderPlayer();


    }

    private function renderTitle(){
        ?>
         <div class="mb-md-5 mt-md-4 pb-5 d-flex justify-content-center"><?=$this->data->title();?></div>
        <?php
    }

    private function renderPlayer(){
        ?>
        <div class="mb-md-5 mt-md-4 pb-5 d-flex justify-content-center">
        <video controls>
        <source src="<?=$this->data->resource_url();?>" type="<?=$this->data->mime_type();?>">
        Your browser does not support the audio element.
        </video>
        </div>
        <?php
    }

    private function renderInfo(){

        ?>
        <?php
    }
    
}