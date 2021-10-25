<?php
namespace bandpress\Actions;

class LogoutAction{
    public function __construct(){}
    public function __destruct(){}
    public function do(){
       wp_logout();
       wp_redirect("/");
       die;
    }

} 