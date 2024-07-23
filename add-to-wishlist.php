<?php
session_start();

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'book_manager2');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['session_token'])) {
    header("Location: login.php");
    exit;
}

// Get the user ID and book ID
$user_id = $_SESSION['user_id'];
$book_id = intval($_GET['book_id']);

// Add book to wishlist
$stmt = $conn->prepare("INSERT INTO wishlist (user_id, book_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $book_id);
$stmt->execute();

header("Location: wishlist.php");
exit;

$stmt->close();
$conn->close();
?>
