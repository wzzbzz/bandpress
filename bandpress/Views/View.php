<?php

namespace bandpress\Views;

class View{

    protected $data;
    private $header;
    private $nav;
    private $body;
    private $footer;

    public function __construct($data=null){
        
        $this->data = $data;
        $this->setHeader();
        $this->setNav();
        $this->setNotifications();
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
        $this->renderNotifications();
        $this->renderBody();
        $this->renderFooter();
    }

    protected function setHeader(){
        $this->header = new \bandpress\Views\ComponentViews\Header($this->data);
    }
    
    protected function renderHeader(){
        $this->header->render();
    }

    protected function setNav(){
        $this->nav = new \bandpress\Views\ComponentViews\Nav($this->data);
    }
    
    protected function renderNav(){
        $this->nav->render();
    }

    protected function setNotifications(){
        $this->notifications = new \bandpress\Views\ComponentViews\Notifications($_SESSION['notifications']);
    }
    protected function renderNotifications(){
        $this->notifications->render();
    }
    protected function renderBody(){
        ?>
        <div class="container">This will be the body</div>
        <?php
    }
    protected function setFooter(){
        $this->footer = new \bandpress\Views\ComponentViews\Footer($this->data);
    }

    protected function renderFooter(){
        $this->footer->render();
    }


}