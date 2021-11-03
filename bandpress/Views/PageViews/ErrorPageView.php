<?php

namespace bandpress\Views\PageViews;
use bandpress\Views\View;

class ErrorPageView extends View{
    public function renderBody(){
    ?>
    <div class="container d-flex justify-content-center">
        <?= $this->data;?>
    </div>
    <?php
    }
}