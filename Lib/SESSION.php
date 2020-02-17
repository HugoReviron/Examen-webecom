<?php

class SESSION {
    public static function Start(){
        if(session_status() === PHP_SESSION_NONE){
            ini_set("session.use_strict_mode", TRUE);
            ini_set("session.cookie_httponly", TRUE);
            session_start();
        }
    }
    
    public static function Kill(){
        session_unset();
        session_destroy();
    }
    
    public static function Init_values($uid, $mail ,$status, $name, $last_name){
        $_SESSION["uid"] = $uid;
        $_SESSION["mail"] = $mail;
        $_SESSION["status"] = $status;
        $_SESSION["name"] = "$name $last_name";
    }
    
    public static function Is_logged(){
        return key_exists("uid", $_SESSION);
    }
    
    public static function Get_uid(){
        return $_SESSION["uid"];
    }
    
    public static function Get_status(){
        return $_SESSION["status"];
    }
    
    public static function Get_name(){
        return $_SESSION["name"];
    }
    
    public static function Get_mail(){
        return $_SESSION["mail"];
    }
}
