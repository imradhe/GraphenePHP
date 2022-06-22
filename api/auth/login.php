<?php
$email = $_POST['email'];
$password = $_POST['password'];

require("api/controllers/AuthController.php");
$auth = new Auth();

echo $auth->login($email, $password);