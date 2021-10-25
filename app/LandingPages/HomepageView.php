<?php

namespace app\LandingPages;

use app\Views\View;

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
        <div class="container text center">
            <h2>You Are Logged In <a href="/actions/logout">Log Out</a></h2>
        </div>
        <?php
    }   

    private function renderGuestUserScreen(){
        $loginform = new \app\Views\ComponentViews\LoginForm();
        $loginform->render();
    }
}