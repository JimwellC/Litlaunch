<?php
session_start();

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not admin
    exit();
}

require_once "../db/config.php";

// Fetch total number of users
$total_users_stmt = $pdo->query("SELECT COUNT(*) FROM users");
$total_users = $total_users_stmt->fetchColumn();

// Fetch total number of books
$total_books_stmt = $pdo->query("SELECT COUNT(*) FROM books");
$total_books = $total_books_stmt->fetchColumn();

// Fetch total number of audit logs (optional)
$total_logs_stmt = $pdo->query("SELECT COUNT(*) FROM audit_logs");
$total_logs = $total_logs_stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Analytics — Admin Panel</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<?php include("../includes/navbar.php"); ?>

<div class="admin-dashboard">
    <h1>Analytics</h1>
    <p>View the overall statistics of your platform</p>

    <!-- Add wrapper container for flexbox -->
    <div class="analytics-card-container">
        <div class="analytics-card">
            <h2>Total Users</h2>
            <p><?= $total_users ?></p>
        </div>

        <div class="analytics-card">
            <h2>Total Books</h2>
            <p><?= $total_books ?></p>
        </div>

        <div class="analytics-card">
            <h2>Total Audit Logs</h2>
            <p><?= $total_logs ?></p>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>

</body>
</html>