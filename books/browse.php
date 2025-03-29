<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Browse Books — LitLaunch</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <?php include("../db/config.php"); ?>
  <?php include("../includes/navbar.php"); ?>

  <div class="hero">
    <h1>📚 Browse Books for Sale</h1>
  </div>

  <div class="books-container">
    <?php
      try {
        $stmt = $pdo->query("SELECT * FROM books");
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($books) {
          foreach ($books as $book) {
            echo "<div class='book-card'>";
            echo "<img src='../assets/" . htmlspecialchars($book['image_url']) . "' alt='Book cover' class='book-image'>";
            echo "<h3>" . htmlspecialchars($book['title']) . "</h3>";
            echo "<p><strong>Author:</strong> " . htmlspecialchars($book['author']) . "</p>";
            echo "<p><strong>Price:</strong> ₱" . htmlspecialchars($book['price']) . "</p>";
            echo "<a href='view.php?id=" . $book['id'] . "'>View</a>";
            echo "</div>";
          }
        } else {
          echo "<p style='text-align: center;'>No books found.</p>";
        }
      } catch (PDOException $e) {
        echo "<p>Error fetching books: " . $e->getMessage() . "</p>";
      }
    ?>
  </div>

  <?php include("../includes/footer.php"); ?>

</body>
</html>