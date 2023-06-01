<?php

/**
 * The App class provides utility methods for the main application.
 */
class App
{
    /**
     * Retrieves the current session information.
     *
     * @return array|false The session information if available, or false otherwise.
     */
    public static function getSession()
    {
        $loginID = $_COOKIE['auth'];
        DB::connect();
        $query = DB::select('logs', '*', "loginID='$loginID' and loggedout=0")->fetchAll();
        DB::close();
        
        if ($query) {
            return $query[0];
        } else {
            return false;
        }
    }

    /**
     * Retrieves the user information from the cookie.
     *
     * @return array|false The user information if available, or false otherwise.
     */
    public static function getUser()
    {
        $loginID = $_COOKIE['auth'];

        DB::connect();
        $query = DB::select('logs', '*', "loginID='$loginID' and loggedout=0")->fetchAll()[0];
        
        if ($query) {
            $email = $query['email'];
            $currentLog = DB::select('users', '*', "email='$email'")->fetchAll();
            DB::close();
            return $currentLog[0];
        } else {
            return false;
        }
    }
}
