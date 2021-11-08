<?php

namespace bandpress\Views\PageViews;
use bandpress\Views\View;

class RegisterPageView extends View{

    public function renderBody(){
        $form = new \bandpress\Views\ComponentViews\RegisterForm();
        $form->render();
    }
}