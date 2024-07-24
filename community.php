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
    if (isset($_POST['comment'])) {
        $book_id = $_POST['book_id'];
        $user_id = $_SESSION['user_id'];
        $comment = $_POST['comment'];

        $sql = "INSERT INTO comments (book_id, user_id, comment) VALUES ('$book_id', '$user_id', '$comment')";
        if ($conn->query($sql) === TRUE) {
            echo "New comment created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['remove_comment_id'])) {
        $comment_id = $_POST['remove_comment_id'];
        $sql = "DELETE FROM comments WHERE id = '$comment_id' AND user_id = '{$_SESSION['user_id']}'";
        if ($conn->query($sql) === TRUE) {
            echo "Comment removed successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Fetch books with comments
$sql = "SELECT b.*, c.comment, c.created_at, c.id as comment_id, u.username
        FROM books b
        LEFT JOIN comments c ON b.id = c.book_id
        LEFT JOIN users u ON c.user_id = u.id
        ORDER BY c.created_at DESC";
$result = $conn->query($sql);

$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[$row['id']]['details'] = [
            'title' => $row['title'],
            'author' => $row['author'],
            'image' => $row['image']
        ];
        if ($row['comment']) {
            $books[$row['id']]['comments'][] = [
                'username' => $row['username'],
                'comment' => $row['comment'],
                'created_at' => $row['created_at'],
                'comment_id' => $row['comment_id']
            ];
        }
    }
}

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
            <?php if (!empty($books)) : ?>
                <?php foreach ($books as $book_id => $book) : ?>
                    <section class="book-details-container">
                        <div class="book-image">
                            <img src="assets/images/<?php echo htmlspecialchars($book['details']['image']); ?>" alt="<?php echo htmlspecialchars($book['details']['title']); ?>">
                        </div>
                        <div class="book-info">
                            <h2><?php echo htmlspecialchars($book['details']['title']); ?></h2>
                            <p><strong>Author:</strong> <?php echo htmlspecialchars($book['details']['author']); ?></p>
                            <?php if (isset($book['comments'])) : ?>
                                <?php foreach ($book['comments'] as $comment) : ?>
                                    <div class="comment-box">
                                        <h4><?php echo htmlspecialchars($comment['username']); ?></h4>
                                        <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                                        <small>Posted on: <?php echo htmlspecialchars($comment['created_at']); ?></small>
                
                                        <form class="remove-comment-form" method="post" action="community.php">
                                            <input type="hidden" name="remove_comment_id" value="<?php echo $comment['comment_id']; ?>">
                                            <button class="remove-button" type="submit">Remove</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p>No comments yet.</p>
                            <?php endif; ?>
                            <form action="community.php" method="post" class="add-comment-form">
                                <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                                <label for="comment-<?php echo $book_id; ?>">Comment:</label>
                                <textarea id="comment-<?php echo $book_id; ?>" name="comment" required></textarea>
                                <button type="submit">Add Comment</button>
                            </form>
                        </div>
                    </section>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No books found.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
