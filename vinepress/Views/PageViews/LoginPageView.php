<?php

namespace vinepress\Views\PageViews;
use vinepress\Views\View;

class LoginPageView extends View{

    public function renderBody(){
        $form = new \vinepress\Views\ComponentViews\LoginForm();
        $form->render();
    }
}