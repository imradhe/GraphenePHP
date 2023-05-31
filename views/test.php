<?php
//errors(1);
controller('Auth');
$auth = new Auth;

$data = [
    'name' => 'Arun Devaputra',
    'email' => 'contact@imradheaaaa.com',
    'phone' => '8839929302',
    'password' => 'Radhe@M022',
    'role' => 'user'
];
//$name, $email, $phone, $password, $role
$result = $auth->register($data['name'], $data['email'], $data['phone'], $data['password'], $data['role']);


echo json_encode($result);