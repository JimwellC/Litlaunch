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
  // Get the current book data from the database
  $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ? AND user_id = ?");
  $stmt->execute([$book_id, $user_id]);
  $book = $stmt->fetch();

  if (!$book) {
    header("Location: my-books.php?error=Book not found or you don't have permission to edit it.");
    exit();
  }
} catch (PDOException $e) {
  header("Location: my-books.php?error=" . urlencode("Error fetching book data: " . $e->getMessage()));
  exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = htmlspecialchars($_POST['title']);
  $author = htmlspecialchars($_POST['author']);
  $price = htmlspecialchars($_POST['price']);

  $image_url = $book['image_url']; // Keep the old image URL by default

  // Check if the user uploaded a new image
  if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $image = $_FILES['image'];

    // Sanitize file name
    $image_name = time() . '-' . basename($image['name']);
    $image_path = '../assets/' . $image_name;

    // Check if the file is an image (basic check)
    if (getimagesize($image['tmp_name']) === false) {
      header("Location: update.php?id=" . $book_id . "&error=File is not an image.");
      exit();
    }

    // Move the uploaded file to the assets folder
    if (move_uploaded_file($image['tmp_name'], $image_path)) {
      $image_url = $image_name; // Update the image URL to the new file
    } else {
      header("Location: update.php?id=" . $book_id . "&error=Error uploading image.");
      exit();
    }
  }

  try {
    // Update the book details in the database
    $stmt = $pdo->prepare("UPDATE books SET title = ?, author = ?, price = ?, image_url = ? WHERE id = ?");
    $stmt->execute([$title, $author, $price, $image_url, $book_id]);

    header("Location: my-books.php?success=Book updated successfully!");
    exit();
  } catch (PDOException $e) {
    header("Location: update.php?id=" . $book_id . "&error=" . urlencode("Error updating book: " . $e->getMessage()));
    exit();
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Book — LitLaunch</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <?php include("../includes/navbar.php"); ?>

  <div class="hero">
    <h1>Edit Book Details</h1>

    <?php if (isset($_GET['error'])): ?>
      <p style="color: #f87171; text-align:center;"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php elseif (isset($_GET['success'])): ?>
      <p style="color: #4ade80; text-align:center;"><?= htmlspecialchars($_GET['success']) ?></p>
    <?php endif; ?>

    <form action="update.php?id=<?= $book['id'] ?>" method="POST" class="login-form" enctype="multipart/form-data">
      <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" placeholder="Book Title" required>
      <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" placeholder="Author" required>
      <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($book['price']) ?>" placeholder="Price (₱)" required>
      <input type="file" name="image" accept="image/*">
      <button type="submit" class="btn">Update Book</button>
    </form>
  </div>

  <?php include("../includes/footer.php"); ?>

</body>
</html>
