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

// Handle adding to wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_wishlist'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Assuming user ID is stored in session
        $book_id_to_add = $_POST['add_to_wishlist'];

        // Insert into wishlist table
        $stmt = $conn->prepare("INSERT INTO wishlist (user_id, book_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $book_id_to_add);

        if ($stmt->execute()) {
            // Redirect to wishlist page after successful addition
            header("Location: wishlist.php");
            exit;
        } else {
            echo "<p>Error adding book to wishlist: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Error: User ID is not set in the session.</p>";
    }
}

// Handle adding to my books
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_my_books'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Assuming user ID is stored in session
        $book_id_to_add = $_POST['add_to_my_books'];

        // Insert into mybooks table
        $stmt = $conn->prepare("INSERT INTO mybooks (user_id, book_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $book_id_to_add);

        if ($stmt->execute()) {
            // Redirect to my books page after successful addition
            header("Location: mybooks.php");
            exit;
        } else {
            echo "<p>Error adding book to my collection: " . $stmt->error . "</p>";
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
                    
                    <!-- Add to Wishlist and My Books Buttons -->
                    <?php if (isset($_SESSION['session_token'])): ?>
                        <div class="button-group">
                            <form action="view-book.php?id=<?php echo $book_id; ?>" method="post" class="wishlist-form">
                                <input type="hidden" name="add_to_wishlist" value="<?php echo $book_id; ?>">
                                <button type="submit" class="wishlist-button">Add to Wishlist</button>
                            </form>
                            <form action="view-book.php?id=<?php echo $book_id; ?>" method="post" class="my-books-form">
                                <input type="hidden" name="add_to_my_books" value="<?php echo $book_id; ?>">
                                <button type="submit" class="my-books-button">Add to My Books</button>
                            </form>
                        </div>
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
