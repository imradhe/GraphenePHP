<?php
class Auth
{
    protected $db;
    protected $errors;

    public function __construct(){
        require('db.php');
        $this->db = new mysqli($config['DB_HOST'],$config['DB_USERNAME'], $config['DB_PASSWORD'], $config['DB_DATABASE']);
        $this->errors = "";
    }

    // Check the session validity
    public function checkSession(){
        $this->loginID = $_COOKIE['auth'];

        $stmt = $this->db->prepare("SELECT * FROM logs WHERE loginID = ? AND loggedout = 0");
        $stmt->bind_param("s", $this->loginID);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $this->currentLog = $result->fetch_assoc();
            return $this->currentLog;
        }
        else{
            return false;
        }
    }

    public function login($email, $password){
        $this->email = trim($email);
        $this->password = $password;

        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();
        $loginQuery = $result->fetch_assoc();

        // Check if user exists
        if($loginQuery){
            // Check if password is correct
            if($loginQuery['password'] == md5($this->password)){
                $this->loginID = md5($this->email.$this->password.time());
                setcookie("auth", $this->loginID, time() + (86400 * 365), "/");

                $this->ip = getDevice()['ip'];
                $this->os = getDevice()['os'];
                $this->browser = getDevice()['browser'];

                $stmt = $this->db->prepare("INSERT INTO logs (`loginID`, `email`, `ip`, `browser`, `os`) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $this->loginID, $this->email, $this->ip, $this->browser, $this->os);
                $stmt->execute();

                if($stmt->affected_rows > 0){
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
    }

    // Check if email exists
    public function check($email, $role){
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows;
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
    $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $getUser = $result->fetch_assoc();
    return $getUser;
}

public function getUsers(){
    $users = array();
    $query = $this->db->query("SELECT * FROM users");
    while ($user = $query->fetch_assoc()) {
        $users[] = $user;
    }
    return $users;
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
    $this->name = trim($this->db->real_escape_string($name));
    $this->email = trim($this->db->real_escape_string($email));
    $this->phone = trim($this->db->real_escape_string($phone));
    $this->password = md5($this->db->real_escape_string($password));
    $this->passwordWithoutMD5 =  $this->db->real_escape_string($password);
    $this->role = trim($this->db->real_escape_string($role));

    $validate = $this->validate($this->name, $this->email, $this->phone, $this->passwordWithoutMD5, $this->role);

    if($validate['error']){
        return ['error' => $validate['error'], 'errorMsgs' => $validate['errorMsgs']];
    }else{
        $stmt = $this->db->prepare("INSERT INTO users (`name`, `email`, `password`, `phone`, `role`) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $this->name, $this->email, $this->password, $this->phone, $this->role);
        $createUser = $stmt->execute();
        $stmt->close();

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
    $this->name = trim($this->db->real_escape_string($data['name']));
    $this->email = trim($this->db->real_escape_string($data['email']));
    $this->phone = trim($this->db->real_escape_string($data['phone']));
    $this->password = $this->db->real_escape_string($data['password']);
    $this->role = trim($this->db->real_escape_string($data['role']));
    $this->status = trim($this->db->real_escape_string($data['status']));
    
    $validate = $this->validateEdit($this->name, $this->email, $this->phone, $this->password, $this->role);

    if($validate['error']){
        return ['error' => $validate['error'], 'errorMsgs' => $validate['errorMsgs']];
    }else{
        $this->password = md5($this->db->real_escape_string($data['password']));
        
        $stmt = $this->db->prepare("UPDATE users SET `name` = ?, `password` = ?, `phone` = ?, `role` = ?, `status` = ? WHERE `email` = ?");
        $stmt->bind_param("ssssis", $this->name, $this->password, $this->phone, $this->role, $this->status, $this->email);
        $updateUser = $stmt->execute();
        $stmt->close();

        if($updateUser){
            $this->error = false;
            $this->errorMsgs['updateUser'] = '';
        }else{
            $this->error = true;
            $this->errorMsgs['updateUser'] = 'User account Updation failed ' . $this->db->error;
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
    $email = $this->db->real_escape_string($email);
  
    $getUserStmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
    $getUserStmt->bind_param("s", $email);
    $getUserStmt->execute();
    $getUserResult = $getUserStmt->get_result();
    $getUserStmt->close();

    if($getUserResult->num_rows === 0) {
        return [
            'error' => true,
            'errorMsg' => 'User not found'
        ];
    }

    $deleteUserStmt = $this->db->prepare("UPDATE users SET status = 0 WHERE email = ?");
    $deleteUserStmt->bind_param("s", $email);
    $deleteUser = $deleteUserStmt->execute();
    $deleteUserStmt->close();

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
    $updateLogStmt = $this->db->prepare("UPDATE logs SET loggedout = 1, loggedoutat = current_timestamp() WHERE loginID = ?");
    $updateLogStmt->bind_param("i", $loginID);
    $updateLog = $updateLogStmt->execute();
    $updateLogStmt->close();

    if($updateLog){
        setcookie("auth", "", time()-(60*60*24*7), "/");
        unset($_COOKIE["auth"]);
        header("Location:".home()."login?loggedout=true");
    }
}

} 

