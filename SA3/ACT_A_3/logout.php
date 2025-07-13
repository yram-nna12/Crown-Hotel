<?php
session_start();

// 1. Unset all session variables
session_unset();

// 2. Destroy the session
session_destroy();

// 3. Clear "Remember Me" cookies if you used them
setcookie("username", "", time() - 3600, "/");
setcookie("password", "", time() - 3600, "/");

// 4. Redirect to login page
header("Location: login.php");
exit;
?>
