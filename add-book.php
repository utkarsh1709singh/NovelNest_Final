<?php
include 'db/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $genre = $conn->real_escape_string($_POST['genre']);
    $published_year = $conn->real_escape_string($_POST['published_year']);
    $description = $conn->real_escape_string($_POST['description']);

    $sql = "INSERT INTO books (title, author, genre, published_year, description)
            VALUES ('$title', '$author', '$genre', '$published_year', '$description')";

    if ($conn->query($sql) === TRUE) {
        echo "New book added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Add a New Book</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="view-books.php">View Books</a></li>
            <li><a href="add-book.php">Add Book</a></li>
        </ul>
    </nav>
    <main>
        <form method="POST" action="add-book.php">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            <br>
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" required>
            <br>
            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre">
            <br>
            <label for="published_year">Published Year:</label>
            <input type="text" id="published_year" name="published_year">
            <br>
            <label for="description">Description:</label>
            <textarea id="description" name="description"></textarea>
            <br>
            <input type="submit" value="Add Book">
        </form>
    </main>
    <script src="assets/js/script.js"></script>
</body>
</html>
