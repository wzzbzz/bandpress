<?php

namespace app\Views;

class View{

    private $data;
    private $header;
    private $nav;
    private $body;
    private $footer;

    public function __construct($data=null){
        $this->data = $data;
        $this->setHeader();
        $this->setNav();
        $this->setFooter();
        $this->user = is_user_logged_in();
    }
    public function __destruct(){}

    public function init(){
        // here we will set our header, nav, body, footer stuff
    }

    public function render(){
        $this->renderHeader();
        $this->renderNav();
        $this->renderBody();
        $this->renderFooter();
    }

    protected function setHeader(){
        $this->header = new \app\Views\ComponentViews\Header($this->data);
    }
    
    protected function renderHeader(){
        $this->header->render();
    }

    protected function setNav(){
        $this->nav = new \app\Views\ComponentViews\Nav($this->data);
    }
    
    protected function renderNav(){
        $this->nav->render();
    }
    protected function renderBody(){
        ?>
        <div class="container">This will be the body</div>
        <?php
    }
    protected function setFooter(){
        $this->footer = new \app\Views\ComponentViews\Footer($this->data);
    }

    protected function renderFooter(){
        $this->footer->render();
    }


}