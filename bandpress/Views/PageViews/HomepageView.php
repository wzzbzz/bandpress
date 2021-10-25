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
        $form = new \bandpress\Views\ComponentViews\UploadForm();
        $form->render();
    }   

    private function renderGuestUserScreen(){
        $form = new \bandpress\Views\ComponentViews\LoginForm();
        $form->render();
    }
}