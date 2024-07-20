<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'book_manager2');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch books based on search query
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ? LIMIT 18");
    $like_query = "%" . $search_query . "%";
    $stmt->bind_param("s", $like_query);
} else {
    $stmt = $conn->prepare("SELECT * FROM books LIMIT 18");
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Collection Manager</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Book Collection Manager</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="add-book.php">Add Book</a></li>
        </ul>
    </nav>
    <main>
        <section id="main-content">
            <!-- Search Form -->
            <form action="index.php" method="get" id="search-form">
                <input type="text" name="search" placeholder="Search books..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Search</button>
            </form>
            
            <!-- Book List -->
            <div class="book-list">
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <div class="book-item" onclick="openDetails(<?php echo $row['id']; ?>)">
                        <img src="assets/images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>
    <script src="assets/js/script.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>