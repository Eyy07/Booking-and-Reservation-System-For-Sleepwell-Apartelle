<?php
// Start the session
session_start();

// Unset all of the session variables to log the user out
$_SESSION = array();

// If there's a session cookie, remove it as well by setting its expiration date to an hour ago
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to the login page or any other page
header("Location: login.html");
exit;
?>
