<?php
session_start();
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_id']);
unset($_SESSION['admin_name']);
session_destroy();
header('Location: login.php');
exit();
?>