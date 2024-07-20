<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'book_manager2');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];

    // Move uploaded image to the server directory
    move_uploaded_file($_FILES['image']['tmp_name'], 'assets/images/' . $image);

    $stmt = $conn->prepare("INSERT INTO books (title, author, description, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $author, $description, $image);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit;
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
        </ul>
    </nav>
    <main>
        <section>
            <div class="add-book-form-container">
                <form action="add-book.php" method="post" enctype="multipart/form-data">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>
                    
                    <label for="author">Author:</label>
                    <input type="text" id="author" name="author" required>
                    
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>
                    
                    <label for="image">Image:</label>
                    <input type="file" id="image" name="image" required>
                    
                    <button type="submit">Add Book</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
