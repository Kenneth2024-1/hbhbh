<?php
// auth/logout.php - Minimal version

session_start();

// Clear everything
$_SESSION = [];
session_destroy();

// Delete session cookie (good practice)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect immediately
header("Location: ../index.php");
exit;
?>