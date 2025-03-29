<?php
session_start(); // Start the session
require_once 'db/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name     = $_POST['name'];
  $email    = $_POST['email'];
  $password = $_POST['password'];
  $confirm  = $_POST['confirm_password'];

  if ($password !== $confirm) {
    die("Passwords do not match.");
  }

  $hashed = password_hash($password, PASSWORD_DEFAULT);

  try {
    // Insert new user into the database
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $hashed]);

    // Log the registration action into the audit logs
    $action = 'User registered';
    $user_id = $pdo->lastInsertId(); // Get the last inserted user ID
    $details = 'New user registered with email: ' . htmlspecialchars($email);

    // Insert audit log entry
    $stmt_log = $pdo->prepare("INSERT INTO audit_logs (action, user_id, details) VALUES (?, ?, ?)");
    $stmt_log->execute([$action, $user_id, $details]);

    // Redirect to the login page with a success message
    header("Location: login.php?registered=1");
    exit();
  } catch (PDOException $e) {
    die("Registration failed: " . $e->getMessage());
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register — LitLaunch</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

  <?php include("includes/navbar.php"); ?>

  <div class="hero">
    <h1>Create Your Account</h1>

    <?php if (isset($_GET['error'])): ?>
      <p style="color: #f87171; text-align:center;"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php elseif (isset($_GET['success'])): ?>
      <p style="color: #4ade80; text-align:center;"><?= htmlspecialchars($_GET['success']) ?></p>
    <?php endif; ?>

    <form action="register.php" method="POST" class="login-form">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button type="submit" class="btn">Register</button>
    </form>
  </div>

  <?php include("includes/footer.php"); ?>

</body>
</html>
