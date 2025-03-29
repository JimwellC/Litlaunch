<?php
session_start();
require_once '../db/config.php';
include("../includes/navbar.php");


if (!isset($_GET['id'])) {
  echo "<p style='text-align: center;'>No book ID provided.</p>";
  include("../includes/footer.php");
  exit();
}

$id = intval($_GET['id']);
try {
  $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
  $stmt->execute([$id]);
  $book = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$book) {
    echo "<p style='text-align: center;'>Book not found.</p>";
    include("../includes/footer.php");
    exit();
  }
} catch (PDOException $e) {
  echo "<p>Error fetching book: " . $e->getMessage() . "</p>";
  include("../includes/footer.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View — LitLaunch</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <div class="book-detail">
    <img src="../assets/<?= htmlspecialchars($book['image_url']) ?>" alt="Book cover">
    <h2><?= htmlspecialchars($book['title']) ?></h2>
    <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
    <p><strong>Price:</strong> ₱<?= htmlspecialchars($book['price']) ?></p>
    <a href="browse.php">← Back to Browse</a>
  </div>

  <?php include("../includes/footer.php"); ?>

</body>
</html>