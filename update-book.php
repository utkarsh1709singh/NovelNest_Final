<?php
include 'db/db.php';

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $sql = "SELECT * FROM books WHERE id=$id";
    $result = $conn->query($sql);
    $book = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $conn->real_escape_string($_POST['id']);
    $title = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $genre = $conn->real_escape_string($_POST['genre']);
    $published_year = $conn->real_escape_string($_POST['published_year']);
    $description = $conn->real_escape_string($_POST['description']);

    $sql = "UPDATE books SET title='$title', author='$author', genre='$genre', published_year='$published_year', description='$description' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Book updated successfully!";
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
    <title>Update Book</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Update Book</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="view-books.php">View Books</a></li>
            <li><a href="add-book.php">Add Book</a></li>
        </ul>
    </nav>
    <main>
        <form method="POST" action="update-book.php">
            <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo $book['title']; ?>" required>
            <br>
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" value="<?php echo $book['author']; ?>" required>
            <br>
            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre" value="<?php echo $book['genre']; ?>">
            <br>
            <label for="published_year">Published Year:</label>
            <input type="text" id="published_year" name="published_year" value="<?php echo $book['published_year']; ?>">
            <br>
            <label for="description">Description:</label>
            <textarea id="description" name="description"><?php echo $book['description']; ?></textarea>
            <br>
            <input type="submit" value="Update Book">
        </form>
    </main>
    <script src="assets/js/script.js"></script>
</body>
</html>
