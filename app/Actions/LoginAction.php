<?php
namespace app\Actions;

class LoginAction{
    public function __construct(){}
    public function __destruct(){}
    public function do(){
        if(empty($_REQUEST['username'])){
            //set system error message somehow.
        }
        else{
            $result = wp_authenticate($_REQUEST['username'],$_REQUEST['password']);
            if (is_wp_error($result)){
                foreach($result->errors as $error=>$desc){
                    echo $error."<br>";
                }
            }
            else{
                wp_signon(array('user_login'=>$_REQUEST['username'],'user_password'=>$_REQUEST['password']));
            }
            
        }
        wp_redirect("/");
        die;
    }

    public function exit(){
        
    }
} 