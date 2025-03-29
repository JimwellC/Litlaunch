<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'user') {
    header("Location: /login.php"); // Redirect to login page if not a user
    exit();
}
require_once '../db/config.php';

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'user') {
  header("Location: ../login.php");
  exit();
}

if (!isset($_GET['id'])) {
  header("Location: my-books.php?error=Book ID not provided.");
  exit();
}

$book_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

try {
  // Check if the book belongs to the logged-in user
  $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ? AND user_id = ?");
  $stmt->execute([$book_id, $user_id]);
  $book = $stmt->fetch();

  if (!$book) {
    header("Location: my-books.php?error=Book not found or you don't have permission to delete it.");
    exit();
  }

  // Get the image path
  $image_path = '../assets/images/' . $book['image_url'];

  // Delete the image file if it exists
  if (file_exists($image_path)) {
    unlink($image_path);  // Delete the image file
  }

  // Display confirmation page
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // If confirmed, delete the book
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
    $stmt->execute([$book_id]);

    header("Location: my-books.php?success=Book deleted successfully!");
    exit();
  }

} catch (PDOException $e) {
  header("Location: my-books.php?error=" . urlencode("Error deleting book: " . $e->getMessage()));
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Confirm Deletion — LitLaunch</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <?php include("../includes/navbar.php"); ?>

  <div class="hero">
    <h1>Are you sure you want to delete this book?</h1>
    <p><strong>Title:</strong> <?= htmlspecialchars($book['title']) ?></p>
    <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>

    <form method="POST">
        <button type="submit" class="btn delete-btn">Yes, Delete</button>
        <button type="button" class="btn cancel-btn" onclick="window.location.href='my-books.php';">Cancel</button>
    </form>
  </div>

  <?php include("../includes/footer.php"); ?>

</body>
</html>