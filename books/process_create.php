<?php
session_start();
require_once '../db/config.php';

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'user') {
  header("Location: ../login.php");
  exit();
}

$title = htmlspecialchars($_POST['title']);
$author = htmlspecialchars($_POST['author']);
$price = htmlspecialchars($_POST['price']);

// Check if the file input exists and is not empty
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $image = $_FILES['image'];

    // Sanitize file name
    $image_name = time() . '-' . basename($image['name']);
    $image_path = '../assets/images/' . $image_name;

    // Check if the file is an image (basic check)
    if (getimagesize($image['tmp_name']) === false) {
        header("Location: create.php?error=File is not an image.");
        exit();
    }

    // Move the uploaded file to the assets folder
    if (!move_uploaded_file($image['tmp_name'], $image_path)) {
        header("Location: create.php?error=Error uploading image.");
        exit();
    }
} else {
    header("Location: create.php?error=Image is required.");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Insert the book details into the database
    $stmt = $pdo->prepare("INSERT INTO books (title, author, price, image_url, user_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $author, $price, $image_name, $user_id]);

    // Redirect back with success
    header("Location: create.php?success=Book added successfully!");
    exit();
} catch (PDOException $e) {
    // Error handling for DB insertion
    header("Location: create.php?error=" . urlencode("Error adding book: " . $e->getMessage()));
    exit();
}
?>