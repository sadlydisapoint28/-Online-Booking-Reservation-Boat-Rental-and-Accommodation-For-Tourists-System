<?php
require_once('../config/connect.php'); // Include database connection
session_start(); // Start the session

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) { // Assuming user ID 1 is admin
    header("Location: login.php"); // Redirect to login if not logged in or not admin
    exit();
}

$totalBoatsStmt = $pdo->query("SELECT * FROM boats"); // Fetch all available boats
$totalBoats = $totalBoatsStmt->fetchAll(); // Store the result in a variable

// Fetch total users
$totalUsersStmt = $pdo->query("SELECT COUNT(*) FROM users");
$totalUsers = $totalUsersStmt->fetchColumn();

// Fetch total bookings
$totalBookingsStmt = $pdo->query("SELECT COUNT(*) FROM bookings");
$totalBookings = $totalBookingsStmt->fetchColumn();

// Fetch recent feedback
$recentFeedbackStmt = $pdo->query("SELECT * FROM feedback ORDER BY created_at DESC LIMIT 5"); // Assuming a 'created_at' column exists
$recentFeedback = $recentFeedbackStmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/dashboard.css"> <!-- Link to external CSS for styling -->
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <div class="overview">
            <h2>Overview</h2>
            <p>Total Users: <strong><?php echo htmlspecialchars($totalUsers); ?></strong></p>
            <p>Total Bookings: <strong><?php echo htmlspecialchars($totalBookings); ?></strong></p>
        </div>

        <div class="available-boats">
            <h2>Available Boats</h2>
            <ul>
                <?php foreach ($totalBoats as $boat): ?>
                    <li><?php echo htmlspecialchars($boat['name']); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="recent-feedback">

            <h2>Recent Feedback</h2>
            <ul>
                <?php foreach ($recentFeedback as $feedback): ?>
                    <li><?php echo htmlspecialchars($feedback['feedback']); ?> - User ID: <?php echo htmlspecialchars($feedback['user_id']); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>
