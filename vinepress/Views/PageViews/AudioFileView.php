<?php

namespace vinepress\Views\PageViews;

use vinepress\Views\View;

class AudioFileView extends View{
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
        <audio controls>
        <source src="<?=$this->data->resource_url();?>" type="<?=$this->data->mime_type();?>">
        Your browser does not support the audio element.
        </audio>
        </div>
        <?php
    }

    private function renderInfo(){

        ?>
        <?php
    }
    
}