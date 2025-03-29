<?php
session_start();

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard — LitLaunch</title>
    <link rel="stylesheet" href="../assets/style.css"> 
</head>
<body>

<?php include("../includes/navbar.php"); ?>

<!-- Admin Dashboard Content -->
<div class="admin-dashboard">
    <h1>Welcome to the Admin Dashboard</h1>
    <p>Manage your application from here</p>

    <!-- Admin Dashboard Sections -->
    <div class="admin-dashboard-content">
        <!-- Section: Manage Users -->
        <div class="dashboard-card">
            <h2>Manage Users</h2>
            <p>View and manage all users in the system</p>
            <a href="manage_user.php" class="btn">Manage Users</a>
        </div>

        <!-- Section: Analytics -->
        <div class="dashboard-card">
            <h2>Analytics</h2>
            <p>View the overall statistics of your platform</p>
            <a href="analytics.php" class="btn">View Analytics</a>
        </div>

        <!-- Section: Audit Logs -->
        <div class="dashboard-card">
            <h2>Audit Logs</h2>
            <p>View logs of user activity and system actions</p>
            <a href="audit_log.php" class="btn">View Logs</a>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>

</body>
</html>