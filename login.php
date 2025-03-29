<?php
require_once 'db/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email    = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  // Check if user exists and password matches
  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['name']      = $user['name'];
    $_SESSION['role']      = $user['role'];

    // Redirect based on the user role
    if ($user['role'] === 'admin') {
      header("Location: admin/index.php");  // Redirect to admin dashboard
      exit();
    } else {
      header("Location: dashboard.php");  // Redirect to user dashboard
      exit();
    }
  } else {
    // If credentials are invalid, redirect to login page with an error message
    header("Location: login.php?error=Invalid email or password.");
    exit();
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login — LitLaunch</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

  <?php include("includes/navbar.php"); ?>

  <div class="hero">
    <h1>Login to LitLaunch</h1>

    <?php if (isset($_GET['error'])): ?>
      <p style="color: #f87171; text-align:center;"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST" class="login-form">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="btn">Login</button>
    </form>
  </div>

  <?php include("includes/footer.php"); ?>

</body>
</html>