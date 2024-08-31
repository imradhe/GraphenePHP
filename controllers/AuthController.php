<?php

/**
 * The Auth class handles authentication and user management.
 *
 * GraphenePHP Auth Controller
 *
 * This class provides validation functionalities for form fields.
 * It allows defining validation rules and callbacks for each field,
 * and returns error messages for invalid fields.
 *
 * @package GraphenePHP
 * @version 2.0.0
 */
class Auth
{
    protected $db;
    protected $loginId;
    protected $currentLog;
    protected $userID;
    protected $email;
    protected $password;
    protected $ip;
    protected $os;
    protected $browser;
    protected $name;
    protected $phone;
    protected $role;
    protected $status;
    protected $errors;

    /**
     * Auth constructor.
     */
    public function __construct()
    {
        $this->errors = [];
    }

    /**
     * Checks the validity of the session.
     *
     * @return array|false The current session information if valid, or false otherwise.
     */
    public function checkSession()
    {
        $this->loginId = $_COOKIE['auth'] ?? null;

        if (!$this->loginId) {
            return false;
        }

        DB::connect();
        $query = DB::select('logs', '*', "loginId = '$this->loginId' AND loggedOutAt IS NULL")->fetch();
        DB::close();

        if ($query) {
            $this->currentLog = $query;
            return $this->currentLog;
        } else {
            return false;
        }
    }

    /**
     * Authenticates the user with the provided email and password.
     *
     * @param string $email The user's email.
     * @param string $password The user's password.
     * @return array The result of the login operation.
     */
    public function login($email, $password)
    {
        DB::connect();
        $this->email = strtolower(trim(DB::sanitize($email)));
        $this->password = md5(DB::sanitize($password));
        DB::close();

        $user = $this->getUserByEmail($this->email);

        if ($user && $user['password'] === $this->password) {
            $this->loginId = md5(sha1($this->email) . sha1($this->password) . sha1(time()));
            setcookie("auth", $this->loginId, time() + (86400 * 365), "/");

            $deviceInfo = getDevice();
            $this->ip = $deviceInfo['ip'];
            $this->os = $deviceInfo['os'];
            $this->browser = $deviceInfo['browser'];

            $time = date_create()->format('Y-m-d H:i:s');
            $data = [
                'loginId' => $this->loginId,
                'userID' => $user['userID'],
                'ip' => $this->ip,
                'browser' => $this->browser,
                'os' => $this->os,
                'loggedInAt' => $time,
            ];

            DB::connect();
            $insertedLog = DB::insert('logs', $data);
            DB::close();

            if ($insertedLog) {
                $redirectUrl = $_GET['back'] ?? route('');
                header("Location: $redirectUrl");
                exit;
            } else {
                $this->errors[] = "Internal Server Error";
            }
        } else {
            $this->errors[] = "Invalid email or password";
        }

        return ['error' => true, 'errorMsgs' => $this->errors];
    }

    /**
     * Checks if an email exists for a specific role.
     *
     * @param string $email The email to check.
     * @param string $role The role to check against.
     * @return bool Returns true if the email exists for the specified role, false otherwise.
     */
    public function check($email, $role)
    {
        DB::connect();
        $result = DB::select('users', '*', "email = '$email' AND role = '$role' AND status <> 'deleted'")->fetchAll();
        DB::close();
        return !empty($result);
    }

    /**
     * Get a user with userID.
     *
     * @param string $userID The userID of the user.
     * @return array The result of the select query.
     */
    public static function getUser($userID)
    {
        DB::connect();
        $userID = DB::sanitize($userID);
        $getUser = DB::select('users', '*', "userID = '$userID' AND status <> 'deleted'")->fetch();
        DB::close();

        if ($getUser) {
            return $getUser;
        } else {
            return ['error' => true, "errorMsgs" => ['user' => "User not found"]];
        }
    }

    /**
     * Get a user with email.
     *
     * @param string $email The user's email.
     * @return array The result of the select query.
     */
    public static function getUserByEmail($email)
    {
        DB::connect();
        $email = DB::sanitize($email);
        $getUser = DB::select('users', '*', "email = '$email' AND status <> 'deleted'")->fetch();
        DB::close();

        if ($getUser) {
            return $getUser;
        } else {
            return ['error' => true, "errorMsgs" => ['user' => "User not found"]];
        }
    }

    /**
     * Get all users.
     * @return array The result of the select query.
     */
    public static function getUsers()
    {
        DB::connect();
        $users = DB::select('users', '*', "status <> 'deleted'")->fetchAll();
        DB::close();

        if ($users) {
            return $users;
        } else {
            return ['error' => true, 'errorMsgs' => ['users' => 'No users found']];
        }
    }

    /**
     * Registers a new user.
     *
     * @param string $name The name of the user.
     * @param string $email The email of the user.
     * @param string $phone The phone number of the user.
     * @param string $password The password for the user account.
     * @param string $role The role of the user.
     * @param string $status The status of the user (default: pending).
     * @return array The result of the registration operation.
     */
    public function register($name, $email, $phone, $password, $role, $status = "pending")
    {
        // Sanitize fields
        DB::connect();
        $this->name = trim(DB::sanitize($name));
        $this->email = strtolower(trim(DB::sanitize($email)));
        $this->phone = trim(DB::sanitize($phone));
        $this->password = md5(DB::sanitize($password));
        $this->role = trim(DB::sanitize($role));
        $this->status = trim(DB::sanitize($status));
        $this->userID = md5(md5($this->email) . md5($this->phone) . time() . uniqid());
        DB::close();

        // Validation rules
        $fields = [
            'name' => [
                'value' => $this->name,
                'rules' => [
                    ['type' => 'required', 'message' => "Name can't be empty"],
                    ['type' => 'minLength', 'message' => "Name can't be less than 6 characters", 'minLength' => 6],
                ],
            ],
            'email' => [
                'value' => $this->email,
                'rules' => [
                    ['type' => 'required', 'message' => "Email can't be empty"],
                    ['type' => 'email', 'message' => 'Email is invalid'],
                    ['type' => 'custom', 'message' => 'Email already in use', 'validate' => function () {
                        return !$this->check($this->email, $this->role);
                    }],
                ],
            ],
            'phone' => [
                'value' => $this->phone,
                'rules' => [
                    ['type' => 'required', 'message' => "Phone can't be empty"],
                    ['type' => 'phone', 'message' => "Invalid phone number"],
                ],
            ],
            'password' => [
                'value' => $this->password,
                'rules' => [
                    ['type' => 'required', 'message' => "Password can't be empty"],
                ],
            ],
        ];

        // Validate fields
        $validate = Validator::validate($fields);
        if ($validate['error']) {
            return ['error' => true, 'errorMsgs' => $validate['errorMsgs']];
        }

        // Create user
        $data = [
            'userID' => $this->userID,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'phone' => $this->phone,
            'role' => $this->role,
            'status' => $this->status,
        ];

        DB::connect();
        $createUser = DB::insert('users', $data);
        DB::close();

        if ($createUser) {
            return ['error' => false, 'message' => 'Registration successful'];
        } else {
            return ['error' => true, 'errorMsgs' => ['createUser' => 'User account creation failed']];
        }
    }

    /**
     * Edits user information.
     *
     * @param string $userID The user ID of the user to be edited.
     * @param array $data The user data to be edited.
     * @return array The result of the edit operation.
     */
    public function edit($userID, $data)
    {
        DB::connect();
        $this->userID = DB::sanitize($userID);
        $this->name = trim(DB::sanitize($data['name']));
        $this->email = trim(DB::sanitize($data['email']));
        $this->phone = trim(DB::sanitize($data['phone']));
        $this->role = trim(DB::sanitize($data['role']));
        $this->status = trim(DB::sanitize($data['status']));
        DB::close();

        // Define validation rules for fields
        $fields = [
            'name' => [
                'value' => $this->name,
                'rules' => [
                    ['type' => 'required', 'message' => "Name can't be empty"],
                    ['type' => 'minLength', 'message' => "Name can't be less than 6 characters", 'minLength' => 6],
                ],
            ],
            'email' => [
                'value' => $this->email,
                'rules' => [
                    ['type' => 'email', 'message' => 'Email is invalid'],
                ],
            ],
            'phone' => [
                'value' => $this->phone,
                'rules' => [
                    ['type' => 'phone', 'message' => "Invalid phone number"],
                ],
            ],
        ];

        // Validate fields
        $validate = Validator::validate($fields);
        if ($validate['error']) {
            return ['error' => true, 'errorMsgs' => $validate['errorMsgs']];
        }

        $updateData = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'status' => $this->status,
        ];

        DB::connect();
        $updateUser = DB::update('users', $updateData, "userID = '$this->userID'");
        DB::close();

        if ($updateUser) {
            return $this->getUser($userID);
        } else {
            return [
                'error' => true,
                'errorMsgs' => ['updateUser' => 'User account update failed'],
            ];
        }
    }

    /**
     * Deletes a user account.
     *
     * @param string $userID The user ID of the user to be deleted.
     * @return array The result of the delete operation.
     */
    public function delete($userID)
    {
        $check = $this->getUser($userID);

        if ($check['error']) {
            return $check;
        }

        DB::connect();
        $deleteLogs = DB::delete('logs', "userID = '$userID'");
        DB::close();

        if (!$deleteLogs) {
            return ["error" => true, "errorMsgs" => ["logs" => "Logs deletion failed"]];
        }

        $data = [
            'status' => 'deleted',
        ];

        DB::connect();
        $deleteUser = DB::update('users', $data, "userID = '$userID'");
        DB::close();

        if ($deleteUser) {
            return [
                'error' => false,
                'message' => $this->getUser($userID)['email'] . " successfully deleted",
            ];
        } else {
            return [
                'error' => true,
                'errorMsgs' => ['deleteUser' => 'Failed to delete user'],
            ];
        }
    }

    /**
     * Changes the user's password.
     *
     * @param string $userID The user ID.
     * @param string $newPassword The new password.
     * @return array The result of the password change operation.
     */
    public function changePassword($userID, $newPassword)
    {
        DB::connect();
        $newPasswordHashed = md5(DB::sanitize($newPassword));
        $updateData = ['password' => $newPasswordHashed];
        $updateUser = DB::update('users', $updateData, "userID = '$userID'");
        DB::close();

        if ($updateUser) {
            return [
                'error' => false,
                'message' => 'Password changed successfully!',
            ];
        } else {
            return [
                'error' => true,
                'errorMsgs' => ['changePassword' => 'Failed to change password. Please try again.'],
            ];
        }
    }

    /**
     * Logs out the user.
     */
    public function logout()
    {
        $loginId = $_COOKIE['auth'] ?? null;

        if (!$loginId) {
            return ['error' => true, 'errorMsg' => 'No active session found'];
        }

        $time = date_create()->format('Y-m-d H:i:s');
        $data = ['loggedOutAt' => $time];

        DB::connect();
        $updateLog = DB::update('logs', $data, "loginId = '$loginId'");
        DB::close();

        if ($updateLog) {
            setcookie("auth", "", time() - 3600, "/");
            unset($_COOKIE["auth"]);
            header("Location:" . route("login") . "?loggedout=true");
            exit;
        } else {
            return ['error' => true, 'errorMsg' => 'Failed to log out. Please try again.'];
        }
    }
}
