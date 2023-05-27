<?php
$_ERRORS = array();

function myErrorHandler($errno, $errstr, $errfile, $errline) {
    global $_ERRORS;
    $_ERRORS[] = array("errno" => $errno, "errstr" => $errstr, "errfile" => $errfile, "errline" => $errline);
}

set_error_handler("myErrorHandler");
trigger_error("");
unset($_ERRORS[0]);
$abc = $bcs;
echo json_encode($_ERRORS);