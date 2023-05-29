<?php
errors(1);
class Auth
{
    protected $db;
    protected $errors;

    public function __construct(){
        $this->errors = "";
    }

    // Check the session validity
    public function checkSession() {
        $this->loginID = $_COOKIE['auth'];
        
        DB::connect();
        $query = DB::select('logs', '*', "loginID = '$this->loginID' AND loggedout = 0")->fetchAll();
        DB::close();
    
        if ($query) {
            $this->currentLog = $query[0];
            return $this->currentLog;
        } else {
            return false;
        }
    }

    public function login($email, $password){
        $this->email = strtolower(trim(DB::sanitize($email)));
        $this->password = DB::sanitize($password);

        
        
        DB::connect();
        $loginQuery = DB::select('users', '*', "email = '$this->email'")->fetchAll()[0];
        //return gettype($loginQuery->fetchAll());
        DB::close();
        // Check if user exists
        if($loginQuery){
            // Check if password is correct
            if($loginQuery['password'] == md5($this->password)){
                
                $this->loginID = md5(sha1($this->email).sha1($this->password).sha1(time()));
                setcookie("auth", $this->loginID, time() + (86400 * 365), "/");

                $this->ip = getDevice()['ip'];
                $this->os = getDevice()['os'];
                $this->browser = getDevice()['browser'];

                $time = date_create()->format('Y-m-d H:i:s');
                $data = [
                    'loginID' => $this->loginID,
                    'email' => $this->email,
                    'ip' => $this->ip,
                    'browser' => $this->browser,
                    'os' => $this->os,
                    'loggedinat' => $time
                ];
                
                DB::connect();
                $insertedLog = DB::insert('logs', $data);
                DB::close();

                if($insertedLog){
                    if(!empty($_GET['back'])){
                        header("Location:".$_GET['back']);
                    }
                    else{
                        header("Location:".home());
                    }
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
        return ['error'=>true, 'errorMsg'=>$this->errors];
    }

    // Check if email exists
    public function check($email, $role){
        DB::connect();
        $result = DB::select('users', '*', "email = '$email' and role = '$role'")->fetchAll();
        DB::close();
        return count($result);
    }
    
    // Form Validations
    public function validate($name, $email, $phone, $password, $role){
        
        // Name Validation
        if(empty($name)){
            $errors['name'] = true;
            $errorMsgs['name'] = "Name can't be empty";
        }elseif(strlen($name)<=5){            
            $errors['name'] = true;
            $errorMsgs['name'] = "Name can't be less than 6 Characters";
        }
        else{
            $errors['name'] = false;
            $errorMsgs['name'] = "";
        }
        

        // Email Validation        
        $emailPattern = "/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/";
        if(empty($email)){
            $errors['email'] = true;
            $errorMsgs['email'] = "Email can't be empty";
        }elseif(!preg_match($emailPattern, $email)){
            $errors['email'] = true;
            $errorMsgs['email'] = "Email is invalid";
        }elseif($this->check($email, $role)){
            $errors['email'] = true;
            $errorMsgs['email'] = "Email already in use";
        }else{
            $errors['email'] = false;
            $errorMsgs['email'] = "";
        }

        
        $phonePattern = "/^([6-9][0-9]{9})$/";

        if(empty($phone)){
          $errors['phone'] = true;
          $errorMsgs['phone'] = "Phone can't be empty";
        }
        elseif(preg_match($phonePattern, $phone)){
          $errors['phone'] = false;   
          $errorMsgs['phone'] = "";
        }
        else{                   
          $errors['phone'] = true;
          $errorMsgs['phone'] = "Invalid Phone";       
        }

        // Password Validation
        if(empty($password)){
            $errors['password'] = true;
            $errorMsgs['password'] = "Password can't be empty";
        }
        elseif(strlen($password)<=5){            
            $errors['password'] = true;
            $errorMsgs['password'] = "Can't be less than 6 Characters";
        }
        elseif(!preg_match('@\W|_@', $password)){            
            $errors['password'] = true;
            $errorMsgs['password'] = "Must Contain atleast one special character";
        }
        elseif(!preg_match('@[0-9]@', $password)){            
            $errors['password'] = true;
            $errorMsgs['password'] = "Must Contain atleast one number";
        }
        elseif(!preg_match('@[a-z]@', $password)){            
            $errors['password'] = true;
            $errorMsgs['password'] = "Must Contain atleast one lowercase";
        }
        elseif(!preg_match('@[A-Z]@', $password)){            
            $errors['password'] = true;
            $errorMsgs['password'] = "Must Contain atleast one lowercase";
        }
        else{
            $errors['password'] = false;
            $errorMsgs['password'] = "";
        }

        // Calculating error
        $error = false;
        foreach ($errors as $i) {
          $error += $i;  
        }
        return ['error'=>$error, 'errorMsgs'=>$errorMsgs];

    }

public function getUser($email){
    DB::connect();
    $getUser = DB::select('users', '*', "email = '$email'")->fetchAll();
    DB::close();
    if($getUser) return $getUser;
    else return false;
}

public function getUsers(){
    DB::connect();
    $users = DB::select('users', '*')->fetchAll();
    DB::close();
    if($users) return $users;
    else return false;
}

public function validateEdit($name, $email, $phone, $password, $role){
    $errors = array();
    $errorMsgs = array();
    
    $user = $this->getUser($email);
    
    if(empty($user)){
        $errors['user'] = true;
        $errorMsgs['user'] = "Invalid User";            
    }else{
        $errors['user'] = false;
        $errorMsgs['user'] = ""; 
    }

    // Name Validation
    if(empty($name)){
        $errors['name'] = true;
        $errorMsgs['name'] = "Name can't be empty";
    }elseif(strlen($name)<=5){            
        $errors['name'] = true;
        $errorMsgs['name'] = "Name can't be less than 6 Characters";
    }else{
        $errors['name'] = false;
        $errorMsgs['name'] = "";
    }
    
    $phonePattern = "/^([6-9][0-9]{9})$/";

    if(empty($phone)){
      $errors['phone'] = true;
      $errorMsgs['phone'] = "Phone can't be empty";
    }elseif(preg_match($phonePattern, $phone)){
      $errors['phone'] = false;   
      $errorMsgs['phone'] = "";
    }else{                   
      $errors['phone'] = true;
      $errorMsgs['phone'] = "Invalid Phone";       
    }

    // Password Validation
    if(empty($password)){
        $errors['password'] = true;
        $errorMsgs['password'] = "Password can't be empty";
    }elseif(strlen($password)<=5){            
        $errors['password'] = true;
        $errorMsgs['password'] = "Can't be less than 6 Characters";
    }elseif(!preg_match('@\W|_@', $password)){            
        $errors['password'] = true;
        $errorMsgs['password'] = "Must Contain at least one special character";
    }elseif(!preg_match('@[0-9]@', $password)){            
        $errors['password'] = true;
        $errorMsgs['password'] = "Must Contain at least one number";
    }elseif(!preg_match('@[a-z]@', $password)){            
        $errors['password'] = true;
        $errorMsgs['password'] = "Must Contain at least one lowercase";
    }elseif(!preg_match('@[A-Z]@', $password)){            
        $errors['password'] = true;
        $errorMsgs['password'] = "Must Contain at least one uppercase";
    }else{
        $errors['password'] = false;
        $errorMsgs['password'] = "";
    }

    // Calculating error
    $error = array_sum($errors);
    return ['error' => $error, 'errorMsgs' => $errorMsgs];
}

public function register($name, $email, $phone, $password, $role){
    DB::connect();
    $this->name = trim(DB::sanitize($name));
    $this->email = strtolower(trim(DB::sanitize($email)));
    $this->phone = trim(DB::sanitize($phone));
    $this->password = md5(DB::sanitize($password));
    $this->passwordWithoutMD5 =  DB::sanitize($password);
    $this->role = trim(DB::sanitize($role));
    
    

    $validate = $this->validate($this->name, $this->email, $this->phone, $this->passwordWithoutMD5, $this->role);

    if($validate['error']){
        return ['error' => $validate['error'], 'errorMsgs' => $validate['errorMsgs']];
    }else{
        
        $data = array(
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'phone' => $this->phone,
            'role' => $this->role
        );
        
        
        DB::connect();
        $createUser = DB::insert('users', $data);
        DB::close();

        if($createUser){
            $this->error = false;
            $this->errorMsgs['createUser'] = '';
        }else{
            $this->error = true;
            $this->errorMsgs['createUser'] = 'User account creation failed';
        }

        if($this->error){
            return ['error' => $this->error, 'errorMsgs' => $this->errorMsgs];
        }else{
            $userLogin = new Auth();
            $userLogin->login($this->email, $this->passwordWithoutMD5);
        }
    }
}

public function edit($data){
    $this->name = trim(DB::sanitize($data['name']));
    $this->email = trim(DB::sanitize($data['email']));
    $this->phone = trim(DB::sanitize($data['phone']));
    $this->password = DB::sanitize($data['password']);
    $this->role = trim(DB::sanitize($data['role']));
    $this->status = trim(DB::sanitize($data['status']));
    
    $validate = $this->validateEdit($this->name, $this->email, $this->phone, $this->password, $this->role);

    if($validate['error']){
        return ['error' => $validate['error'], 'errorMsgs' => $validate['errorMsgs']];
    }else{
        $this->password = md5($data['password']);
        
        $data = array(
            'name' => $this->name,
            'password' => $this->password,
            'phone' => $this->phone,
            'role' => $this->role,
            'status' => $this->status
        );
        
        DB::connect();
        $updateUser = DB::update('users', $data, "email = '$this->email'");
        DB::close();

        if($updateUser){
            $this->error = false;
            $this->errorMsgs['updateUser'] = '';
        }else{
            $this->error = true;
            $this->errorMsgs['updateUser'] = 'User account Updation failed ';
        }

        if($this->error){
            return ['error' => $this->error, 'errorMsgs' => $this->errorMsgs];
        }else{
            return [
                'email' => $this->email,
                'name' => $this->name,
                'phone' => $this->phone,
                'password' => $this->password,
                'role' => $this->role
            ];
        }
    }
}

public function delete($email) {

    if(!$this->getUser($email)) {
        return [
            'error' => true,
            'errorMsg' => 'User not found'
        ];
    }
    
        $data = array(
            'status' => 1
        );
        
        DB::connect();
        $deleteUser = DB::update('users', $data, "email = '$email'");
        DB::close();

    if($deleteUser){
        return [
            'error' => false,
            'errorMsg' => ''
        ];
    }else{
        return [
            'error' => true,
            'errorMsg' => 'Failed to delete user ' . $this->error
        ];
    }
}

// Logout Function
public function logout(){
    $loginID = App::getSession()['loginID'];
    $time = date_create()->format('Y-m-d H:i:s');
    
    
        $data = array(
            'loggedout' => 1,
            'loggedoutat' => $time
        );
        
        DB::connect();
        $updateLog = DB::update('logs', $data, "loginID = '$loginID'");
        DB::close();
        
    if($updateLog){
        setcookie("auth", "", time()-(60*60*24*7), "/");
        unset($_COOKIE["auth"]);
        header("Location:".home()."login?loggedout=true");
    }
}

} 

