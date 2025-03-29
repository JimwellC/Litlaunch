<?php
session_start();
require_once '../db/config.php';

// Check if the user is logged in and has the right role
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

// Get the submitted data
$title = $_POST['title'];
$author = $_POST['author'];
$price = $_POST['price'];
$image_url = $_POST['image_url'];

// Prepare the SQL query to insert the book into the database
$stmt = $pdo->prepare("INSERT INTO books (title, author, price, image_url, user_id) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$title, $author, $price, $image_url, $_SESSION['user_id']]);

// Redirect to the create page with a success message
header("Location: create.php?success=Book added successfully!");
exit();
?>