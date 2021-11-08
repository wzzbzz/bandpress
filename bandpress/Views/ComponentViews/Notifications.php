<?php

namespace bandpress\Views\ComponentViews;


class Notifications{

    private $notifications;

    public function __construct($data=null){
        $this->notifications = $data;
    }

    public function __destruct(){

    }

    public function render(){
        foreach($_SESSION['notifications'] as $type=>$messages){
            switch($type){
                case "errors":
                    $class='alert-danger';
                    break;
                case "successes":
                    $class="alert-success";
                    break;
            }
           ?>
           <div class='alert <?=$class?>'>
           <?php
            foreach($messages as $message){
            ?>
                <div><?=$message;?></div>
           <?php
            }
            ?>
            </div>
            
            <?php
            // clear session;  use an object to encapsulate
            $_SESSION['notifications'] = array();
        }
        
    }

    private function hasErrors(){
        return !empty($this->notifications['errors']);
    }

}
?>