<?php
session_start();

$_SESSION = array();

session_destroy();

if (isset($_COOKIE['user_remember'])) {
    setcookie('user_remember', '', time() - 3600, '/');
}
header('Location: users_login.php');
exit();
?>