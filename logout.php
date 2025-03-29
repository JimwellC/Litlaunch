<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db/config.php';  // Make sure the database connection is included

// Get user details from session
$user_id = $_SESSION['user_id'];
$action = 'User logged out';
$details = 'User logged out successfully at ' . date('Y-m-d H:i:s');

// Insert logout action into audit_logs table
$stmt = $pdo->prepare("INSERT INTO audit_logs (action, user_id, details) VALUES (?, ?, ?)");
$stmt->execute([$action, $user_id, $details]);

// Destroy the session to log out the user
session_destroy();

// Redirect the user to the homepage or login page
header("Location: /index.php");  // Redirect to homepage or login page
exit;
?>