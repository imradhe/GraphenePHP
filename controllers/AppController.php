<?php
class App{


    
    // Get Current Session
    public static function getSession(){
        $loginID = $_COOKIE['auth'];
        DB::connect();
        $query = DB::select('logs', '*', "loginID='$loginID' and loggedout=0")->fetchAll();
        DB::close();
        if($query){
        return $query[0];
        }
        else return false;
    }  
  

    // Get User from cookie
    public static function getUser(){
        $loginID = $_COOKIE['auth'];
    
        DB::connect();
        $query = DB::select('logs', '*', "loginID='$loginID' and loggedout=0")->fetchAll()[0];
        if($query){
            $email = $query['email'];
            $currentLog = DB::select('users', '*', "email='$email'")->fetchAll();
            DB::close();
            return $currentLog[0];
        }
        else return false;
    }
  
    

}