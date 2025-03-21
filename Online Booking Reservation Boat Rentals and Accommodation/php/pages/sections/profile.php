<?php
require_once('../config/connect.php'); // Include database connection
session_start(); // Start the session

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user bookings from the database
$stmt = $pdo->prepare("SELECT * FROM bookings WHERE user_id = ?");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <h1>User Profile</h1>
    <h2>Your Bookings</h2>
    <table>
        <tr>
            <th>Booking ID</th>
            <th>Boat ID</th>
            <th>Date</th>
        </tr>
        <?php foreach ($bookings as $booking): ?>
        <tr>
            <td><?php echo htmlspecialchars($booking['id']); ?></td>
            <td><?php echo htmlspecialchars($booking['boat_id']); ?></td>
            <td><?php echo htmlspecialchars($booking['date']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
