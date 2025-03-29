<?php
require_once '../db/config.php';
include("../includes/navbar.php");


if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'user') {
  header("Location: ../login.php");
  exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Books — LitLaunch</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <div class="hero">
    <h1>📚 My Book Listings</h1>
  </div>

  <div class="books-container">
    <?php
      try {
        $stmt = $pdo->prepare("SELECT * FROM books WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($books) {
          foreach ($books as $book) {
            echo "<div class='book-card'>";
            echo "<img src='../assets/" . htmlspecialchars($book['image_url']) . "' alt='Book cover' class='book-image'>";
            echo "<h3>" . htmlspecialchars($book['title']) . "</h3>";
            echo "<p><strong>Author:</strong> " . htmlspecialchars($book['author']) . "</p>";
            echo "<p><strong>Price:</strong> ₱" . htmlspecialchars($book['price']) . "</p>";
            echo "<a href='update.php?id=" . $book['id'] . "'>Edit</a> | ";
            echo "<a href='delete.php?id=" . $book['id'] . "' onclick='return confirm(\"Are you sure you want to delete this book?\");'>Delete</a>";
            echo "</div>";
          }
        } else {
          echo "<p style='text-align: center;'>You have not added any books yet.</p>";
        }
      } catch (PDOException $e) {
        echo "<p>Error fetching your books: " . $e->getMessage() . "</p>";
      }
    ?>
  </div>

  <?php include("../includes/footer.php"); ?>

</body>
</html>
