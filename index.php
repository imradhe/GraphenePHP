<?php

session_start();
require('config.php');
require('headers.php');
require('functions.php');
errors(0);
controller('App');
require('models/db.php');
require('router.php');

date_default_timezone_set('Asia/Kolkata');

$router = new Router($_SERVER);

$router->run();


