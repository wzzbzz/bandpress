<?php

namespace vinepress\Views\PageViews;
use vinepress\Views\View;

class ErrorPageView extends View{
    public function renderBody(){
    ?>
    <div class="container d-flex justify-content-center">
        <?= $this->data;?>
    </div>
    <?php
    }
}