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

<div class="container">
  <div class="row">
    <div class="col-sm mb-5">
    <span class="display-5 text-center">Fork The Internet.</span><br>
    <span class="heading-4">At the present we offer one product.  You may use it.</span>
    </div>

    <div class="col-sm col-lg-8">
      <div class="card">
        <div class="card-header">
          Sports
        </div>
        <div class="card-body">
          <h5 class="card-title">The Pickin' Chicken!</h5>
          <p class="card-text">Pick NBA Games against the spread in a friendly contest against a other humans and a random Chicken! <i>Seriously, someone's gotta shut that chicken up.</i></p>
          <a href="/pickenchicken" class="btn btn-primary">Beat The Chicken Now!</a>
        </div>
      </div>
    </div>
  </div>
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
    
                    <h2 class="display-5 fw-bold mb-2 text-uppercase">You have come to a Fork In The Internet.com!</h2>
                    <p class="text-white mb-5">Please <a class="btn btn-light text-dark fw-bold" href="/register">register</a> or <a class="btn btn-light text-dark fw-bold" href="/login">sign in</a> to see what's inside!</p>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
        <?php
    }
}