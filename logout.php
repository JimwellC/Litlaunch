<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destroy the session to log out the user
session_destroy();

// Redirect the user to the homepage or login page
header("Location: /index.php");  // Redirect to homepage or login page
exit;
?>