<?php
require('config.php');
$con = mysqli_connect($config['DB_HOST'],$config['DB_USERNAME'], $config['DB_PASSWORD'], $config['DB_DATABASE']);