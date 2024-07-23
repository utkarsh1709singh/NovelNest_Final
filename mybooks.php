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

// Fetch user's books
$user_id = $_SESSION['user_id']; // Assuming user ID is stored in session
$stmt = $conn->prepare("SELECT b.* FROM books b JOIN mybooks mb ON b.id = mb.book_id WHERE mb.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Books</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>My Books</h1>
    </header>
    <nav>
        <ul>
        <li><a href="index.php">Home</a></li>
            <li><a href="wishlist.php">Wishlist</a></li>
            <li><a href="mybooks.php">My Books</a></li>
            <li><a href="profile.php" class="profile-button">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <main>
        <section class="book-list">
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="book-item">
                    <a href="view-my-book.php?id=<?php echo $row['id']; ?>">
                        <img src="assets/images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                    </a>
                </div>
            <?php endwhile; ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Book Collection Manager</p>
    </footer>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
