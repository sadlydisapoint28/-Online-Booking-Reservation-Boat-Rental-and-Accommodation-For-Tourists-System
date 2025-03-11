<?php
require_once('../config/connect.php'); // Include database connection
session_start(); // Start the session

// Fetch all boats from the database
$stmt = $pdo->query("SELECT * FROM boats"); 
$boats = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = $_POST['search_term'];
    // Fetch boats based on search term
    $stmt = $pdo->prepare("SELECT * FROM boats WHERE name LIKE ?");
    $stmt->execute(['%' . $search_term . '%']);
    $boats = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Boats</title>
</head>
<body>
    <h1>Search for Boats</h1>
    <form method="POST" action="">
    <input type="text" name="search_term" placeholder="Enter boat name" required>
    <select name="boat_type">
        <option value="all">All Types</option>
        <option value="sailboat">Sailboat</option>
        <option value="motorboat">Motorboat</option>
        <option value="yacht">Yacht</option>
    </select>

        <input type="submit" value="Search">
    </form>

    <h2>Available Boats</h2>
    <ul>
        <?php foreach ($boats as $boat): ?>
            <li><?php echo htmlspecialchars($boat['name']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
