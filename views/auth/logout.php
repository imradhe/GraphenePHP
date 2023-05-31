<?php
if (App::getSession()) {
    controller('Auth');
    $auth = new Auth();
    $auth->logout();
} else {
    header('Location:' . route('login'));
}
?>