<?php
session_start();

// Unset & destroy session variables
$_SESSION = array();
session_destroy();

header("location: quiz_login.php");
exit;
?>
