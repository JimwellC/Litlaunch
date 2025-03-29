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

  <!-- Search form -->
  <form method="GET" action="browse.php" class="search-form">
    <input type="text" name="search" placeholder="Search by title, author, or price" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
    <button type="submit" class="btn">Search</button>
  </form>

  <?php
    // Pagination and search logic
    $books_per_page = 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $books_per_page;
    $search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : null;

    try {
        // Modify SQL query for search functionality
        if ($search) {
          $stmt = $pdo->prepare("SELECT * FROM books WHERE title LIKE :search OR author LIKE :search OR price LIKE :search LIMIT :limit OFFSET :offset");
          $stmt->bindParam(':search', $search, PDO::PARAM_STR);
      } else {
          $stmt = $pdo->prepare("SELECT * FROM books LIMIT :limit OFFSET :offset");
      }
      
      $stmt->bindParam(':limit', $books_per_page, PDO::PARAM_INT);
      $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        // Bind pagination parameters
        $stmt->bindParam(':limit', $books_per_page, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($books) {
            echo "<div class='books-container'>";
            foreach ($books as $book) {
                echo "<div class='book-card'>";
                echo "<img src='/assets/images/" . htmlspecialchars($book['image_url']) . "' alt='Book cover' class='book-image'>";
                echo "<h3>" . htmlspecialchars($book['title']) . "</h3>";
                echo "<p><strong>Author:</strong> " . htmlspecialchars($book['author']) . "</p>";
                echo "<p><strong>Price:</strong> ₱" . htmlspecialchars($book['price']) . "</p>";
                echo "<a href='view.php?id=" . $book['id'] . "'>View</a>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p style='text-align: center;'>No books found.</p>";
        }

        // Pagination links
        $total_books_stmt = $pdo->query("SELECT COUNT(*) FROM books");
        $total_books = $total_books_stmt->fetchColumn();
        $total_pages = ceil($total_books / $books_per_page);

        echo "<div class='pagination'>";
        for ($i = 1; $i <= $total_pages; $i++) {
            $active_class = ($i === $page) ? 'active' : '';
            echo "<a href='browse.php?page=$i' class='$active_class'>$i</a> ";
        }
        echo "</div>";

    } catch (PDOException $e) {
        echo "<p>Error fetching books: " . $e->getMessage() . "</p>";
    }
  ?>

  <?php include("../includes/footer.php"); ?>

</body>
</html>