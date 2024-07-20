<?php
include 'db.php';

// Fetch books from the database
$sql = "SELECT * FROM books";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
        <h1>My Book Tracker</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="add-book.php">Add Book</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
        </ul>
    </nav>
    <main>
        <div id="main-content">
            <form method="GET" action="dashboard.php">
                <input type="text" name="search" placeholder="Search books...">
                <button type="submit">Search</button>
            </form>

            <h2>Books</h2>
            <div class="book-list">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='book-item'>";
                        echo "<h3>" . $row["title"] . "</h3>";
                        echo "<p>Author: " . $row["author"] . "</p>";
                        echo "<p>Genre: " . $row["genre"] . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "No books found.";
                }
                ?>
            </div>
        </div>
    </main>
</body>
</html>
