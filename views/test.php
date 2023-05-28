<?php
echo $config['DB_HOST']." ".$config['DB_USERNAME']." ".$config['DB_PASSWORD']." ".$config['DB_DATABASE'];
DB::connect($config['DB_HOST'], $config['DB_USERNAME'], $config['DB_PASSWORD'], $config['DB_DATABASE']);

// Select records
$users = DB::select('users', '*', '10');
print_r($users);