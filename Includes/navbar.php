<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<nav class="navbar">
  <div class="navbar-container">
    <a href="/index.php" class="logo">🚀 LitLaunch</a> <!-- Absolute path here -->
    <ul class="nav-links">
      <li><a href="/index.php">Home</a></li> <!-- Absolute path for Home -->
      
      <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
        <?php if ($_SESSION['role'] === 'admin'): ?>
          <!-- Admin specific links -->
          <li><a href="/admin/index.php">Admin Dashboard</a></li>
          <li><a href="/admin/audit_log.php">Audit Logs</a></li>
          <li><a href="/admin/analytics.php">Analytics</a></li>
        <?php else: ?>
          <!-- User specific links for books -->
          <li><a href="/books/browse.php">Browse Books</a></li>
          <li><a href="/books/create.php">Add New Book</a></li>
          <li><a href="/books/my-books.php">My Books</a></li>
        <?php endif; ?>
        <li><a href="/logout.php">Logout</a></li>
      <?php else: ?>
        <!-- Links for non-logged-in users -->
        <li><a href="/login.php">Login</a></li>
        <li><a href="/register.php">Register</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
