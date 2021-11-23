<?php

namespace vinepress\Views\ComponentViews;

class RegisterForm{
    public function __construct(){

    }

    public function render(){
        ?>
        <div class="container py-5 h-100">
        
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card bg-dark text-white" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">

            <div class="mb-md-5 mt-md-4 pb-5">

                <h2 class="fw-bold mb-2 text-uppercase">Register</h2>
                <p class="text-white-50 mb-5">Choose a username and a password</p>
              
                <form action="/actions/registerUser" method="POST">
                    <div class="form-outline form-white mb-4">
                        <input type="text" id="typeUserNameX" class="form-control form-control-lg" name="username"/>
                        <label class="form-label" for="typeUserNameX">Username</label>
                    </div>

                    <div class="form-outline form-white mb-4">
                        <input type="password" id="typePasswordX" class="form-control form-control-lg" name="password" />
                        <label class="form-label" for="typePasswordX">Password</label>
                    </div>
                    <div class="form-outline form-white mb-4">
                        <input type="password" id="typePasswordX" class="form-control form-control-lg" name="repeat-password" />
                        <label class="form-label" for="typePasswordX">Repeat Password</label>
                    </div>

                    <div class="form-outline form-white mb-4">
                        <input type="text" id="Email" class="form-control form-control-lg" name="email" />
                        <label class="form-label" for="typePasswordX">Email Address: (can be bogus)</label>
                    </div>

                    <p class="small mb-5 pb-lg-2 text-white">Write this stuff down I'm not doing anything fancy yet</p>

                    <button class="btn btn-outline-light btn-lg px-5" type="submit">Register</button>

                    <div class="d-flex justify-content-center text-center mt-4 pt-1">
                        <a href="#!" class="text-white"><i class="fab fa-facebook-f fa-lg"></i></a>
                        <a href="#!" class="text-white"><i class="fab fa-twitter fa-lg mx-4 px-2"></i></a>
                        <a href="#!" class="text-white"><i class="fab fa-google fa-lg"></i></a>
                    </div>
                </form>
            </div>

            <div>
              <p class="mb-0">Don't have an account? <a href="#!" class="text-white-50 fw-bold">Sign Up</a></p>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
        <?php
    }
}