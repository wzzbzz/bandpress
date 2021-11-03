<?php

namespace bandpress\Views\PageViews;

use bandpress\Views\View;

class HomepageView extends View{
    protected function renderBody(){

        if (is_user_logged_in()):
            $this->renderLoggedInUserScreen();
        
        else:
            $this->renderGuestUserScreen();    
        endif;

    }
 
    private function renderLoggedInUserScreen(){
        ?>
        <div class="container d-flex">
        <?php
        $form = new \bandpress\Views\ComponentViews\UploadForm();
        $form->render();
        $form = new \bandpress\Views\ComponentViews\AddBandForm();
        $form->render();
        $form = new \bandpress\Views\ComponentViews\AddSongForm();
        $form->render();
        ?>
        </div>
        <?php
    }   

    private function renderGuestUserScreen(){
        $form = new \bandpress\Views\ComponentViews\LoginForm();
        $form->render();
    }
}