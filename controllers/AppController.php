<?php

class App{

    // Get Current Session
    public static function getSession(){
        $loginID = $_COOKIE['auth'];
        
        require("db.php");
        $query = mysqli_fetch_assoc(mysqli_query($con,"SELECT * from logs where loginID='$loginID' and loggedout=0"));
        
        if($query){
        return $query;
        }
        else return false;
    }  
  

    // Get User from cookie
    public static function getUser(){
        $loginID = $_COOKIE['auth'];
    
        require("db.php");
        $query = mysqli_fetch_assoc(mysqli_query($con,"SELECT * from logs where loginID='$loginID' and loggedout=0"));
    
        if($query){
            $email = $query['email'];
            $currentLog = mysqli_fetch_assoc(mysqli_query($con,"SELECT * from users where email='$email'"));
            return $currentLog;
        }
        else return false;
    }
  
    

}