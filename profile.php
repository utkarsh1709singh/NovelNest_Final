<?php
session_start();

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'book_manager2');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user details
if (isset($_SESSION['session_token'])) {
    $stmt = $conn->prepare("
        SELECT u.username, u.email, u.created_at 
        FROM users u 
        INNER JOIN sessions s ON u.id = s.user_id 
        WHERE s.session_token = ?
    ");
    $stmt->bind_param("s", $_SESSION['session_token']);
    $stmt->execute();
    $stmt->bind_result($username, $email, $created_at);
    $stmt->fetch();
    $stmt->close();
} else {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="assets/css/profile.css">
</head>
<body>
    <header>
        <h1>Profile</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <main>
        <section id="profile-content">
            <h2><?php echo htmlspecialchars($username); ?></h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Joined on:</strong> <?php echo htmlspecialchars($created_at); ?></p>
        </section>
    </main>
</body>
</html>

<?php
$conn->close();
?>
