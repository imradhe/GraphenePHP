<?php
class Auth
{
    protected $email;
    protected $password;
    protected $con;
    protected $loginID;
    protected $currentLog;
    protected $ip;
    protected $browser;
    protected $os;
    public $errors;

    public function __construct(){
        // Require Database
        require('db.php');

        // Database Instantiation
        $this->db = new mysqli($config['DB_HOST'],$config['DB_USERNAME'], $config['DB_PASSWORD'], $config['DB_DATABASE']);

        $this->errors = "";

    }


    // Check the session validity
    public function checkSession(){
        $this->loginID = $_COOKIE['auth'];

        $query = mysqli_num_rows($this->db->query("SELECT * from logs where loginID='$this->loginID' and loggedout=0"));

        if($query){
            $this->currentLog = mysqli_fetch_assoc($this->db->query("SELECT * from logs where loginID='$this->loginID' and loggedout=0"));
            return $this->currentLog;
        }

        else return false;

    }
    

    public function login($email, $password){


        $this->email = trim(mysqli_real_escape_string($this->db, $email));
        $this->password = mysqli_real_escape_string($this->db, $password);


        $loginQuery = mysqli_fetch_assoc($this->db->query("SELECT * from users where email='$this->email'"));

        // Check if user exists
        if($loginQuery){

            // Check if password is correct
            if($loginQuery['password'] == md5($this->password)){


                $this->loginID = md5($this->email.$this->password.time());
                // Set Cookie
                setcookie("auth", $this->loginID, time() + (86400 * 365), "/");
    
                $this->ip = getDevice()['ip'];
                $this->os = getDevice()['os'];
                $this->browser = getDevice()['browser'];
    
                // Insert log
                $insertLog = $this->db->query("INSERT INTO logs (`loginID`, `email`, `ip`, `browser`, `os`) VALUES ('$this->loginID','$this->email','$this->ip','$this->browser','$this->os')");
    
                if($insertLog){
                    if(!empty($_GET['back'])) header("Location:".$_GET['back']);
                    else header("Location:".home());
                }
                else{
                    $this->errors = "Internal Server Error";
                }
            }
            else{
                $this->errors = "Password Doesn't Match";
            }
        }
        else{
            $this->errors = "User Not Found";
        }
    }


    // Logout Function
    public function logout(){
        $loginID = getSession()['loginID'];
        $time = date_create()->format('Y-m-d H:i:s');
        $updateLog = $this->db->query("UPDATE logs SET loggedout = 1, loggedoutat = current_timestamp()	 WHERE loginID = '$loginID'");
        if($updateLog){
            setcookie("auth", "", time()-(60*60*24*7),"/");
            unset($_COOKIE["auth"]);
            header("Location:".home()."login?loggedout=true");
        }
    }
} 

