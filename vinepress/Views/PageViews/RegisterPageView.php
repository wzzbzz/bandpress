<?php

namespace vinepress\Views\PageViews;
use vinepress\Views\View;

class RegisterPageView extends View{

    public function renderBody(){
        $form = new \vinepress\Views\ComponentViews\RegisterForm();
        $form->render();
    }
}