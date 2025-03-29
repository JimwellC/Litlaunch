<?php 
ob_start(); // Start output buffering
session_start(); // Start session
?>
<nav class="navbar">
  <div class="navbar-container">
    <a href="/index.php" class="logo">🚀 LitLaunch</a>
    <ul class="nav-links">
      <!-- Links based on session data -->
      <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
        <!-- Admin specific links -->
        <?php if ($_SESSION['role'] === 'admin'): ?>
          <li><a href="/admin/index.php">Admin Dashboard</a></li>
          <li><a href="/admin/audit_log.php">Audit Logs</a></li>
          <li><a href="/admin/analytics.php">Analytics</a></li>
          <li><a href="/admin/manage_user.php">Manage User</a></li>
        <?php else: ?>
          <!-- User specific links -->
          <li><a href="/books/browse.php">Browse Books</a></li>
          <li><a href="/books/create.php">Add New Book</a></li>
          <li><a href="/books/my-books.php">My Books</a></li>
        <?php endif; ?>
        <li><a href="/logout.php">Logout</a></li>
      <?php else: ?>
        <!-- Links for non-logged-in users -->
        <li><a href="/index.php">Home</a></li>
        <li><a href="/books/browse.php">Browse Books</a></li>
        <li><a href="/login.php">Login</a></li>
        <li><a href="/register.php">Register</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>