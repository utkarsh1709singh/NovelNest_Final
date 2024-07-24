<?php
session_start(); // Start session to manage user authentication

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'book_manager2');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch book details
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($book_id > 0) {
    // Get book details from the books table
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    // Check if book exists
    if (!$book) {
        echo "<p>Book not found.</p>";
        $stmt->close();
        $conn->close();
        exit;
    }
} else {
    echo "<p>Invalid book ID.</p>";
    $conn->close();
    exit;
}

// Handle removal from my books
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_book_id'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Assuming user ID is stored in session
        $book_id_to_remove = $_POST['remove_book_id'];

        // Delete from mybooks table
        $stmt = $conn->prepare("DELETE FROM mybooks WHERE user_id = ? AND book_id = ?");
        $stmt->bind_param("ii", $user_id, $book_id_to_remove);

        if ($stmt->execute()) {
            // Redirect to my books page after successful removal
            header("Location: mybooks.php");
            exit;
        } else {
            echo "<p>Error removing book from collection: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Error: User ID is not set in the session.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Book Details</h1>
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
        <section class="book-details">
            <div class="book-details-container">
                <div class="book-image">
                    <img src="assets/images/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                </div>
                <div class="book-info">
                    <h2><?php echo htmlspecialchars($book['title']); ?></h2>
                    <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($book['description']); ?></p>
                    
                    <!-- Remove from My Books Button -->
                    <?php if (isset($_SESSION['session_token'])): ?>
                        <form action="view-my-book.php?id=<?php echo $book_id; ?>" method="post" class="remove-form">
                            <input type="hidden" name="remove_book_id" value="<?php echo $book_id; ?>">
                            <button type="submit" class="remove-button">Remove from My Books</button>
                        </form>
                    <?php else: ?>
                        <p>Please <a href="login.php">log in</a> to manage your books.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Book Collection Manager</p>
    </footer>
</body>
</html>
