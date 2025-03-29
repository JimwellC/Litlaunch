<?php
session_start();

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not admin
    exit();
}

require_once "../db/config.php";

// Fetch all audit logs from the database
$stmt = $pdo->prepare("SELECT audit_logs.*, users.name FROM audit_logs JOIN users ON audit_logs.user_id = users.id ORDER BY audit_logs.timestamp DESC");
$stmt->execute();
$audit_logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Audit Logs — Admin Panel</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<?php include("../includes/navbar.php"); ?>

<div class="admin-dashboard">
    <h1>Audit Logs</h1>
    <p>View logs of user activity and system actions</p>

    <table class="audit-log-table">
        <thead>
            <tr>
                <th>Action</th>
                <th>User</th>
                <th>Timestamp</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($audit_logs as $log): ?>
                <tr>
                    <td><?= htmlspecialchars($log['action']) ?></td>
                    <td><?= htmlspecialchars($log['name'] ?? 'Unknown') ?></td> <!-- Use 'name' and default to 'Unknown' if null -->
                    <td><?= htmlspecialchars($log['timestamp']) ?></td>
                    <td><?= htmlspecialchars($log['details']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include("../includes/footer.php"); ?>

</body>
</html>