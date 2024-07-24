<?php
session_start(); // Start session to manage user authentication

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

// Handle form submission for comments
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['user_id'];
    $comment = $_POST['comment'];

    $sql = "INSERT INTO comments (book_id, user_id, comment) VALUES ('$book_id', '$user_id', '$comment')";
    if ($conn->query($sql) === TRUE) {
        echo "New comment created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch books with comments
$sql = "SELECT b.*, c.comment, c.created_at, u.username
        FROM books b
        JOIN comments c ON b.id = c.book_id
        JOIN users u ON c.user_id = u.id
        ORDER BY c.created_at DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Page</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Community Page</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="wishlist.php">Wishlist</a></li>
            <li><a href="mybooks.php">My Books</a></li>
            <li><a href="community.php">Community</a></li>
            <li><a href="profile.php" class="profile-button">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <main>
        <div class="comments-container">
            <?php if ($result->num_rows > 0) : ?>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <section class="book-details-container">
                        <div class="book-image">
                            <img src="assets/images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        </div>
                        <div class="book-info">
                            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                            <p><strong>Author:</strong> <?php echo htmlspecialchars($row['author']); ?></p>
                            <div class="comment-box">
                                <h4><?php echo htmlspecialchars($row['username']); ?></h4>
                                <p><?php echo htmlspecialchars($row['comment']); ?></p>
                                <small>Posted on: <?php echo $row['created_at']; ?></small>
                            </div>
                        </div>
                    </section>
                <?php endwhile; ?>
            <?php else : ?>
                <p>No comments yet.</p>
            <?php endif; ?>
            <form action="community.php" method="post">
                <label for="book_id">Book ID:</label>
                <input type="text" id="book_id" name="book_id" required>
                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment" required></textarea>
                <button type="submit">Submit Comment</button>
            </form>
        </div>
    </main>
</body>
</html>