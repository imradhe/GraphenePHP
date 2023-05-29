<?php
errors(1);
controller('Auth');
$auth = new Auth;

$data = [
    'name' => 'Arun Devaputra',
    'email' => 'arun@graphenephp.com',
    'phone' => '8839929302',
    'password' => 'Radhe@M022',
    'role' => 'user'
];
//$name, $email, $phone, $password, $role
$result = $auth->delete($data['email']);

echo json_encode($result);

