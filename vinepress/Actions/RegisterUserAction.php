<?php
namespace vinepress\Actions;

class RegisterUserAction{
    public function __construct(){}
    public function __destruct(){}
    public function do(){
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        $confirm = $_REQUEST['repeat-password'];
        $email = $_REQUEST['email'];
        
        if(empty($username)){
            $_SESSION['notifications']['errors'][] = "User name empty";
        }

        if($password != $confirm){
            $_SESSION['notifications']['errors'][] = "Passwords don't match";
        
            }
        if(username_exists($username)){
            $_SESSION['notifications']['errors'][] = "Username Exists; pick another or <a href='/login'>log in.</a>";
        }
        if(!empty($_SESSION['notifications'])){
            wp_redirect("/register");
            die;
        }

        else{
            $result = wp_create_user($username,$password, $email);

            if (is_wp_error($result)){
                foreach($result->errors as $error=>$desc){
                    $_SESSION['notifications']['errors'][]=$desc[0];
                }
                wp_redirect('/register');
                die;
            }
            else{
                $_SESSION['notifications']['successes'][] = "User {$username} successfully created! you can log in now";
                wp_redirect ('/');
                //wp_signon(array('user_login'=>$_REQUEST['username'],'user_password'=>$_REQUEST['password']));
            }
            
        }
        wp_redirect("/");
        die;
    }

    public function exit(){
        
    }
} 