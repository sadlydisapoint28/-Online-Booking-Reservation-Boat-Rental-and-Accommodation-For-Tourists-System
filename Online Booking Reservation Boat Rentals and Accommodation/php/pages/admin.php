<?php
require_once('../config/connect.php'); // Include database connection
session_start(); // Start the session

// Check if the user is an admin (this is a placeholder; implement actual admin check)
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) { // Assuming user ID 1 is admin
    header("Location: login.php"); // Redirect to login if not logged in or not admin
    exit();
}

// Fetch all bookings from the database
$stmt = $pdo->query("SELECT * FROM bookings"); // Assuming a 'bookings' table exists
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <h2>Manage Bookings</h2>
    <table>
        <tr>
            <th>Booking ID</th>
            <th>User ID</th>
            <th>Boat ID</th>
            <th>Date</th>
        </tr>
        <?php foreach ($bookings as $booking): ?>
        <tr>
            <td><?php echo htmlspecialchars($booking['id']); ?></td>
            <td><?php echo htmlspecialchars($booking['user_id']); ?></td>
            <td><?php echo htmlspecialchars($booking['boat_id']); ?></td>
            <td><?php echo htmlspecialchars($booking['date']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
