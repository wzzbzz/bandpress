<?php

namespace vinepress\Views;

class View{

    protected $data;
    protected $header;
    protected $nav;
    protected $body;
    protected $footer;

    public function __construct($data=null){

        // context-particular data
        $this->data = $data;

        // user logged in state / user_id
        $this->user = is_user_logged_in();

        // start with the header
        $this->header = new \vinepress\Views\ComponentViews\Header($this->data);

        // nav component;  pass along the data
        $this->nav = new \vinepress\Views\ComponentViews\Nav($this->data);
        
        $this->notifications = new \vinepress\Views\ComponentViews\Notifications($_SESSION['notifications']);
        $this->footer = new \vinepress\Views\ComponentViews\Footer($this->data);

    }
    public function __destruct(){}

    public function init(){
        
        $this->setNavItems();
        $this->setHeaderItems();
        $this->setFooterItems();

    }

    public function render(){

        $this->renderHeader();
        $this->renderNav();
        $this->renderNotifications();
        $this->renderBody();
        $this->renderFooter();
    }

    protected function setHeaderItems(){
        $this->header->setPageTitle("Forktheinternet.com!");
    }
    
    protected function renderHeader(){
        $this->header->render();
    }

    protected function setNavItems( $items ){
        if($this->user){
            
        }
        else{
            
        }
    }
    
    protected function renderNav(){
        $this->nav->render();
    }

    protected function setNotifications(){
        
    }

    protected function renderNotifications(){
        $this->notifications->render();
    }
    protected function renderBody(){
        ?>
        <div class="container">This will be the body</div>
        <?php
    }
    protected function setFooterItems(){
        
    }

    protected function renderFooter(){
        $this->footer->render();
    }


}