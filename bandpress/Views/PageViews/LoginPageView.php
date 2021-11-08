<?php

namespace bandpress\Views\PageViews;
use bandpress\Views\View;

class LoginPageView extends View{

    public function renderBody(){
        $form = new \bandpress\Views\ComponentViews\LoginForm();
        $form->render();
    }
}