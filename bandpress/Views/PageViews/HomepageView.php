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
        ?>
        <div class="container py-5 h-100">
        
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card bg-dark text-white" style="border-radius: 1rem;">
              <div class="card-body p-5 text-center">
    
                <div class="mb-md-5 mt-md-4 pb-5">
    
                    <h2 class="fw-bold mb-2 text-uppercase">Welcome!</h2>
                    <p class="text-white mb-5">Please <a class="text-white fw-bold" href="/login">sign in</a> or <a class="text-white fw-bold" href="/register">register</a> to see what we're about</p>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
        <?php
    }
}