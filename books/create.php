<?php
session_start();
require_once '../db/config.php';
include("../includes/navbar.php");

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'user') {
  header("Location: ../login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Book — LitLaunch</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="hero">
  <h1>Add a New Book</h1>

  <?php if (isset($_GET['success'])): ?>
    <p style="color: #4ade80; text-align:center;"><?= htmlspecialchars($_GET['success']) ?></p>
  <?php elseif (isset($_GET['error'])): ?>
    <p style="color: #f87171; text-align:center;"><?= htmlspecialchars($_GET['error']) ?></p>
  <?php endif; ?>

  <form action="process_create.php" method="POST" class="login-form">
    <input type="text" name="title" placeholder="Book Title" required>
    <input type="text" name="author" placeholder="Author" required>
    <input type="number" step="0.01" name="price" placeholder="Price (₱)" required>
    <input type="text" name="image_url" placeholder="Image File Name (e.g. book1.jpg)" required>
    <button type="submit" class="btn">Add Book</button>
  </form>
</div>

<?php include("../includes/footer.php"); ?>

</body>
</html>
